<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\Order;
use App\Models\OrderDetail;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

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

<<<<<<< HEAD
        $order = Order::create([
=======
        $orderId = DB::table('orders')->insertGetId([
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            'user_id' => session()->get('user_id'),
            'sub_total' => $subTotal,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'create_date' => date('Y-m-d'),
            'status' => 0,
            'checkout_data' => '',
        ]);

        foreach ($cart as $item) {
<<<<<<< HEAD
            OrderDetail::create([
=======
            DB::table('order_details')->insert([
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
                'item' => $item['name'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'sub_total' => $item['price'] * $item['qty'],
<<<<<<< HEAD
                'order_id' => $order->id,
=======
                'order_id' => $orderId,
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
                'date_added' => date('Y-m-d'),
            ]);
        }

<<<<<<< HEAD
        return $order->id;
=======
        return $orderId;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
    }

    public function saveFormData(int $orderId, array $formData): void
    {
<<<<<<< HEAD
        Order::where('id', $orderId)->update([
=======
        DB::table('orders')->where('id', $orderId)->update([
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            'checkout_data' => json_encode($formData),
        ]);
    }
}
