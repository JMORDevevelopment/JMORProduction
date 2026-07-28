<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\SaveCheckoutFormRequest;
use App\Services\CartService;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home');
        }

        return view('frontend.checkout', [
            'cartItems' => collect($this->cart->items())->keyBy('id')->all(),
            'title' => 'Check Out',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function confirm()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home');
        }

        return view('frontend.checkout_confirm', [
            'cartItems' => collect($this->cart->items())->keyBy('id')->all(),
            'title' => 'Checkout Confirm',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function placeOrder()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home');
        }

        $orderId = $this->checkout->createOrder($this->cart->items());
        session()->put('order_id', $orderId);

        return session()->has('user_id') ? redirect()->route('checkout') : redirect()->route('login');
    }

    public function placeOrderGiftCard()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home');
        }

        $orderId = $this->checkout->createOrder($this->cart->items());
        session()->put('order_id', $orderId);

        return session()->has('user_id') ? redirect()->route('checkout.confirm') : redirect()->route('login');
    }

    public function saveFormData(SaveCheckoutFormRequest $request)
    {
        $this->checkout->saveFormData(session()->get('order_id'), $request->all());

        return redirect()->route('checkout.confirm');
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
