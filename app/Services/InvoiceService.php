<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    public function sendOrderInvoice(
        int $orderId,
        string $transactionId,
        object $order,
        object $customer,
        iterable $orderDetails,
        float $subTotal,
        float $discount,
        float $amount
    ): void {
        $data = [
            'orderId' => $orderId,
            'transactionId' => $transactionId,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'subTotal' => $subTotal,
            'discount' => $discount,
            'amount' => $amount,
        ];

        $subject = 'Order Invoice';

        Mail::send('mails.order-invoice', $data, function ($mail) use ($customer, $subject) {
            $mail->to($customer->email)->subject($subject)->from('Info@jmor.com');
        });

        $adminEmail = DB::table('settings')->where('option', 'email')->value('value');

        if ($adminEmail) {
            Mail::send('mails.order-invoice', $data, function ($mail) use ($adminEmail, $subject) {
                $mail->to($adminEmail)->subject($subject)->from('noreply@jmor.com');
            });
        }
    }
}
