<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Ports CI Dashboard::index().
     *
     * CI logic:
     *   $data_order       = orders WHERE user_id = ?                      -> num_rows()
     *   $new_order        = orders WHERE user_id = ? AND status = 1       -> num_rows()
     *   $completed_order  = orders WHERE user_id = ? AND status = 2       -> num_rows()
     *   $nations          = transaction WHERE user_id = ? AND order_type != 'Gift Card'
     *                       ORDER BY id DESC LIMIT 5
     *                       (+ per-row lookup of orders.status via order_id)
     */
    public function index()
    {
        $user_id = Auth::id();

        $totalOrders = Order::where('user_id', $user_id)->count();
        $processingOrders = Order::where('user_id', $user_id)->where('status', 1)->count();
        $completedOrders = Order::where('user_id', $user_id)->where('status', 2)->count();

        $recentTransactions = Transaction::with('order')
            ->where('user_id', $user_id)
            ->where('order_type', '!=', 'Gift Card')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'title' => 'Index',
            'totalOrders' => $totalOrders,
            'processingOrders' => $processingOrders,
            'completedOrders' => $completedOrders,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    /**
     * Ports CI Dashboard::orders().
     *
     * CI logic:
     *   $trans_datass = transaction WHERE user_id = ? AND order_type != 'Gift Card'
     *                   ORDER BY id DESC
     *                   (no server-side pagination; DataTables handles it client-side)
     */
    public function orders()
    {
        $user_id = Auth::id();

        $transactions = Transaction::with('order')
            ->where('user_id', $user_id)
            ->where('order_type', '!=', 'Gift Card')
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.orders', [
            'title' => 'Orders',
            'transactions' => $transactions,
        ]);
    }

    /**
     * Ports CI Dashboard::order_invoice($order_id).
     *
     * CI logic:
     *   $trans_datass = transaction WHERE order_id = ? AND user_id = ? AND order_type != 'Gift Card'
     *                   -> row_array()
     *   The view then pulls in: user (name + billing address), orders.checkout_data (JSON,
     *   split into "Other Info" scalar fields and "System Information" array fields),
     *   orders.status, order_details rows, and orders.sub_total/discount/grand_total
     *   (falling back to a computed subtotal from order_details when those are empty).
     *
     * NOTE: In CI, if no matching transaction row exists, the view silently continues
     * with undefined array keys (PHP notices, broken output) rather than failing cleanly.
     * That's not meaningful behavior to reproduce, so this port 404s instead when the
     * order isn't found or doesn't belong to the current user.
     */
    public function orderInvoice($order_id)
    {
        $user_id = Auth::id();

        $transaction = Transaction::where('order_id', $order_id)
            ->where('user_id', $user_id)
            ->where('order_type', '!=', 'Gift Card')
            ->first();

        if (! $transaction) {
            abort(404);
        }

        $order = Order::find($order_id);
        $customer = User::find($transaction->user_id);
        $orderDetails = OrderDetail::where('order_id', $order_id)->get();

        $checkoutData = [];
        if ($order && $order->checkout_data) {
            $checkoutData = json_decode($order->checkout_data, true) ?: [];
        }

        // "Other Info" box: scalar (non-array) checkout_data values.
        $otherInfo = array_filter($checkoutData, fn ($value) => ! is_array($value));

        // "System Information" table: array-valued checkout_data entries.
        $systemInfo = array_filter($checkoutData, fn ($value) => is_array($value));

        // Fallback subtotal from order_details, used when orders.sub_total/discount/grand_total are empty.
        $computedSubtotal = $orderDetails->sum('sub_total');

        return view('dashboard.invoice', [
            'title' => 'Invoice',
            'transaction' => $transaction,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'otherInfo' => $otherInfo,
            'systemInfo' => $systemInfo,
            'computedSubtotal' => $computedSubtotal,
        ]);
    }
}
