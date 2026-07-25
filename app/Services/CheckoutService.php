<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;

class CheckoutService
{
    /**
     * Create an order + its order_details rows from the current cart.
     * Returns the new order id.
     */
    public function createOrder(array $cart): int
    {
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += $item['price'] * $item['qty'];
        }

        $discount = session()->get('discount_value', 0);
        $grandTotal = $subTotal - $discount;

        $order = Order::query()->create([
            'user_id' => session()->get('user_id'),
            'sub_total' => $subTotal,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'create_date' => date('Y-m-d'),
            'status' => 0,
            'checkout_data' => '',
        ]);

        foreach ($cart as $item) {
            OrderDetail::query()->create([
                'item' => $item['name'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'sub_total' => $item['price'] * $item['qty'],
                'order_id' => $order->id,
                'date_added' => date('Y-m-d'),
            ]);
        }

        return $order->id;
    }

    public function saveFormData(int $orderId, array $formData): void
    {
        Order::query()->where('id', $orderId)->update([
            'checkout_data' => json_encode($formData),
        ]);
    }
}
