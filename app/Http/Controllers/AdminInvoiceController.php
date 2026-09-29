<?php

namespace App\Http\Controllers;

use App\Models\CouponCheckout;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;

class AdminInvoiceController extends Controller
{
    /**
     * Ports CI admin Orders::transaction_invoice($order_id) — renders the
     * printable invoice for any order (admins are not scoped to a user).
     */
    public function orderInvoice(int $order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)
            ->where('order_type', '!=', 'Gift Card')
            ->first();

        abort_unless($transaction, 404);

        $order = Order::find($order_id);
        $customer = User::find($transaction->user_id);
        $orderDetails = OrderDetail::where('order_id', $order_id)->get();

        $checkoutData = [];
        if ($order && $order->checkout_data) {
            $checkoutData = json_decode($order->checkout_data, true) ?: [];
        }

        $otherInfo = array_filter($checkoutData, fn ($value) => ! is_array($value));
        $systemInfo = array_filter($checkoutData, fn ($value) => is_array($value));
        $computedSubtotal = $orderDetails->sum('sub_total');

        return view('admin.invoice_print', [
            'transaction' => $transaction,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'otherInfo' => $otherInfo,
            'systemInfo' => $systemInfo,
            'computedSubtotal' => $computedSubtotal,
        ]);
    }

    /**
     * Ports CI admin Orders_gift_card::transaction_invoice($order_id).
     */
    public function giftcardInvoice(int $order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)
            ->where('order_type', 'Gift Card')
            ->first();

        abort_unless($transaction, 404);

        $order = Order::find($order_id);
        $customer = User::find($transaction->user_id);
        $orderDetails = OrderDetail::where('order_id', $order_id)->get();
        $couponCheckout = CouponCheckout::where('order_id', $order_id)->first();
        $computedSubtotal = $orderDetails->sum('sub_total');

        return view('admin.giftcard_invoice_print', [
            'transaction' => $transaction,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'couponCheckout' => $couponCheckout,
            'computedSubtotal' => $computedSubtotal,
        ]);
    }
}
