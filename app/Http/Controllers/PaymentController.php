<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\ChargeCreditCardRequest;
use App\Services\CartService;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private CartService $cart
    ) {
    }

    public function chargeCreditCard(ChargeCreditCardRequest $request)
    {
        $orderId = session()->get('order_id');
        if (! $orderId) {
            return redirect()->route('checkout.confirm', ['failed' => 'true']);
        }

        $validated = $request->validated();

        $success = $this->payments->chargeOrder($orderId, [
            'number' => $validated['number'],
            'expiry' => $validated['expiry'],
            'cvc' => $validated['cvc'],
        ]);

        if (! $success) {
            return redirect()->route('checkout.confirm', ['failed' => 'true']);
        }

        $this->cart->clearAfterOrder();

        return redirect()->route('checkout.success');
    }
}
