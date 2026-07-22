<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private CartService $cart
    ) {
    }

    public function chargeCreditCard(Request $request)
    {
        $orderId = session()->get('order_id');
        if (! $orderId) {
            return redirect('/checkout-confirm?failed=true');
        }

        $post = $request->all();
        if (empty($post['number']) || empty($post['expiry']) || empty($post['cvc'])) {
            return redirect('/checkout-confirm?failed=true');
        }

        $success = $this->payments->chargeOrder($orderId, [
            'number' => $post['number'],
            'expiry' => $post['expiry'],
            'cvc' => $post['cvc'],
        ]);

        if (! $success) {
            return redirect('/checkout-confirm?failed=true');
        }

        $this->cart->clearAfterOrder();

        return redirect('/checkout-success');
    }
}
