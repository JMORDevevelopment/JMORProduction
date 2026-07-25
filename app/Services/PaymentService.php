<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class PaymentService
{
    public function __construct(private InvoiceService $invoices)
    {
    }

    /**
     * Charge the card on file for $orderId and finalize the order.
     * Returns true on success, false on any failure (card declined,
     * missing order/customer, gateway error, etc).
     */
    public function chargeOrder(int $orderId, array $cardInput): bool
    {
        $order = DB::table('orders')->where('id', $orderId)->first();
        if (! $order) {
            return false;
        }

        if (session()->get('user_id')) {
            DB::table('orders')->where('id', $orderId)->update(['user_id' => session()->get('user_id')]);
            $order = DB::table('orders')->where('id', $orderId)->first();
        }

        $customer = DB::table('user')->where('user_id', $order->user_id)->first();
        if (! $customer) {
            return false;
        }

        $orderDetails = DB::table('order_details')->where('order_id', $orderId)->get();

        $response = $this->submitToGateway($orderId, $order->grand_total, $customer, $cardInput);

        if (! $this->wasSuccessful($response)) {
            return false;
        }

        $transactionResponse = $response->getTransactionResponse();
        $transactionId = $transactionResponse->getTransId();
        $authCode = $transactionResponse->getAuthCode();

        $this->recordTransaction($orderId, $order, $orderDetails, $transactionId, $authCode);
        DB::table('orders')->where('id', $orderId)->update(['status' => 1]);

        $this->redeemCouponIfApplied();
        $this->generateGiftCardCouponIfNeeded($orderId, $orderDetails);

        $this->invoices->sendOrderInvoice(
            $orderId,
            $transactionId,
            $order,
            $customer,
            $orderDetails,
            $order->sub_total,
            $order->discount,
            $order->grand_total
        );

        return true;
    }

    private function submitToGateway(int $orderId, float $amount, object $customer, array $cardInput)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType;
        $merchantAuthentication->setName(config('services.authorize_net.login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize_net.transaction_key'));

        $creditCard = new AnetAPI\CreditCardType;
        $creditCard->setCardNumber(str_replace(' ', '', $cardInput['number']));
        $creditCard->setExpirationDate(str_replace(' ', '', str_replace('/', '-', $cardInput['expiry'])));
        $creditCard->setCardCode($cardInput['cvc']);

        $paymentOne = new AnetAPI\PaymentType;
        $paymentOne->setCreditCard($creditCard);

        $orderInfo = new AnetAPI\OrderType;
        $orderInfo->setInvoiceNumber($orderId);
        $orderInfo->setDescription('Order #'.$orderId);

        $customerAddress = new AnetAPI\CustomerAddressType;
        $customerAddress->setFirstName($customer->firstname);
        $customerAddress->setLastName($customer->lastname);
        $customerAddress->setCompany($customer->company ?? '');
        $customerAddress->setAddress($customer->address);
        $customerAddress->setCity($customer->city);
        $customerAddress->setState($customer->state);
        $customerAddress->setZip($customer->zip);
        $customerAddress->setCountry('USA');

        $customerData = new AnetAPI\CustomerDataType;
        $customerData->setType('individual');
        $customerData->setId($orderId);
        $customerData->setEmail($customer->email);

        $duplicateWindowSetting = new AnetAPI\SettingType;
        $duplicateWindowSetting->setSettingName('duplicateWindow');
        $duplicateWindowSetting->setSettingValue('60');

        $transactionRequestType = new AnetAPI\TransactionRequestType;
        $transactionRequestType->setTransactionType('authCaptureTransaction');
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($orderInfo);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

        $requestObj = new AnetAPI\CreateTransactionRequest;
        $requestObj->setMerchantAuthentication($merchantAuthentication);
        $requestObj->setRefId('ref'.time());
        $requestObj->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requestObj);
        $environment = config('services.authorize_net.environment') === 'production'
            ? ANetEnvironment::PRODUCTION
            : ANetEnvironment::SANDBOX;

        return $controller->executeWithApiResponse($environment);
    }

    private function wasSuccessful($response): bool
    {
        if ($response === null || $response->getMessages()->getResultCode() != 'Ok') {
            return false;
        }

        $transactionResponse = $response->getTransactionResponse();

        return $transactionResponse !== null && $transactionResponse->getMessages() !== null;
    }

    private function recordTransaction(int $orderId, object $order, iterable $orderDetails, string $transactionId, ?string $authCode): void
    {
        $firstDetail = collect($orderDetails)->first();

        DB::table('transaction')->insert([
            'order_id' => $orderId,
            'order_type' => $firstDetail->type ?? '',
            'checkout_type' => session()->get('checkout_type', 'Monthly'),
            'transaction_id' => $transactionId,
            'user_id' => $order->user_id,
            'amount' => $order->grand_total,
            'auth_code' => $authCode,
        ]);
    }

    private function redeemCouponIfApplied(): void
    {
        if (! session()->has('coupon_code')) {
            return;
        }

        DB::table('coupon_checkout')
            ->where('coupon_number', session()->get('coupon_code'))
            ->update(['status' => 1]);
    }

    private function generateGiftCardCouponIfNeeded(int $orderId, iterable $orderDetails): void
    {
        if (! session()->has('gift_cards')) {
            return;
        }

        $firstDetail = collect($orderDetails)->first();
        if (! $firstDetail || $firstDetail->type !== 'Gift Card') {
            return;
        }

        $giftCard = DB::table('gift_card')->where('name', $firstDetail->item)->first();
        if (! $giftCard) {
            return;
        }

        $couponNumber = strtoupper(substr(md5(time()), 0, 7));

        DB::table('coupon_checkout')->insertGetId([
            'gift_card_id' => $giftCard->id,
            'order_id' => $orderId,
            'coupon_number' => $couponNumber,
        ]);

        // Watermarked coupon image generation (CI legacy feature) — not yet
        // ported; revisit with Intervention Image if it's still needed.
    }
}
