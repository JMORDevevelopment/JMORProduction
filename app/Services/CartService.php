<?php

namespace App\Services;

class CartService
{
    public function resetForPackagePurchase(): void
    {
        session()->forget(['order_id', 'gift_cards', 'checkout_type']);
        session()->put('cart', []);
    }

    public function resetForGiftCardPurchase(): void
    {
        session()->forget(['order_id', 'gift_cards']);
        session()->put('cart', []);
    }

    public function addLines(array $lines): void
    {
        $cart = session()->get('cart', []);
        session()->put('cart', array_merge($cart, $lines));
    }

    public function replace(array $cart): void
    {
        session()->put('cart', $cart);
    }

    public function setCheckoutType(string $type): void
    {
        session()->put('checkout_type', $type);
    }

    public function checkoutType(): string
    {
        return session()->get('checkout_type', 'Monthly');
    }

    public function markGiftCard(string $giftCardId): void
    {
        session()->put('gift_cards', $giftCardId.'-gd');
    }

    public function items(): array
    {
        return session()->get('cart', []);
    }

    public function isEmpty(): bool
    {
        return empty($this->items());
    }

    public function clearAfterOrder(): void
    {
        session()->forget(['cart', 'order_id', 'coupon_code', 'discount_value', 'gift_cards', 'checkout_type']);
    }
}
