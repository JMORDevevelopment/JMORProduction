<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout
    ) {
    }

    public function index()
    {
        if (! session()->has('user_id')) {
            return redirect('/login');
        }

        if ($this->cart->isEmpty()) {
            return redirect('/');
        }

        return view('frontend.checkout', [
            'cartItems' => $this->cart->items(),
            'title' => 'Check Out',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function confirm()
    {
        if (! session()->has('user_id')) {
            return redirect('/login');
        }

        if ($this->cart->isEmpty()) {
            return redirect('/');
        }

        return view('frontend.checkout_confirm', [
            'cartItems' => $this->cart->items(),
            'title' => 'Checkout Confirm',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function placeOrder()
    {
        if ($this->cart->isEmpty()) {
            return redirect('/');
        }

        $orderId = $this->checkout->createOrder($this->cart->items());
        session()->put('order_id', $orderId);

        return session()->has('user_id') ? redirect('/checkout') : redirect('/login');
    }

    public function placeOrderGiftCard()
    {
        if ($this->cart->isEmpty()) {
            return redirect('/');
        }

        $orderId = $this->checkout->createOrder($this->cart->items());
        session()->put('order_id', $orderId);

        return session()->has('user_id') ? redirect('/checkout-confirm') : redirect('/login');
    }

    public function saveFormData(Request $request)
    {
        $this->checkout->saveFormData(session()->get('order_id'), $request->all());

        return redirect('/checkout-confirm');
    }

    public function success()
    {
        return view('frontend.checkout-sucess', [
            'title' => 'Order Confirmed',
            'description' => '',
            'keywords' => '',
        ]);
    }
}
