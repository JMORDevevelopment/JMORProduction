<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
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
}
