<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

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

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => session()->get('user_id'),
            'sub_total' => $subTotal,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'create_date' => date('Y-m-d'),
            'status' => 0,
            'checkout_data' => '',
        ]);

        foreach ($cart as $item) {
            DB::table('order_details')->insert([
                'item' => $item['name'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'sub_total' => $item['price'] * $item['qty'],
                'order_id' => $orderId,
                'date_added' => date('Y-m-d'),
            ]);
        }

        return $orderId;
    }

    public function saveFormData(int $orderId, array $formData): void
    {
        DB::table('orders')->where('id', $orderId)->update([
            'checkout_data' => json_encode($formData),
        ]);
    }
}
