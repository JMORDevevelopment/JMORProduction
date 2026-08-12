<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\TestimonialRequest;
use App\Http\Requests\Dashboard\UpdateUserSettingsRequest;
use App\Models\CouponCheckout;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Testimonial;
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

    public function userSettings()
    {
        $user = User::find(Auth::id());

        return view('dashboard.settings', [
            'title' => 'Setting',
            'user' => $user,
        ]);
    }

    public function userSettingsUpdate($user_id)
    {
        $user = User::find($user_id);

        if (! $user || (int) $user->user_id !== (int) Auth::id()) {
            abort(404);
        }

        return view('dashboard.settings_add', [
            'title' => 'Setting',
            'user' => $user,
            'link' => route('dashboard.user_settings_validate', $user->user_id),
            'cancel' => route('dashboard.user_settings'),
        ]);
    }

    public function userSettingsValidate($user_id, UpdateUserSettingsRequest $request)
    {
        if ((int) $user_id !== (int) Auth::id()) {
            abort(404);
        }

        $post = $request->except('_token');

        if (! empty($post['password'])) {
            $post['password'] = md5($post['password']);
        } else {
            unset($post['password']);
        }

        User::where('user_id', $user_id)->update($post);

        return redirect()->route('dashboard.user_settings');
    }

    /**
     * Ports CI Dashboard::giftcard().
     *
     * CI logic:
     *   $trans_datass = transaction WHERE user_id = ? AND order_type = 'Gift Card'
     *                   ORDER BY id DESC
     *                   (no server-side pagination; DataTables handles it client-side)
     */
    public function giftcard()
    {
        $user_id = Auth::id();

        $transactions = Transaction::with('order')
            ->where('user_id', $user_id)
            ->where('order_type', 'Gift Card')
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.giftcard', [
            'title' => 'Gift card',
            'transactions' => $transactions,
        ]);
    }

    /**
     * Ports CI Dashboard::giftcard_invoice($order_id).
     *
     * CI logic:
     *   $trans_datass = transaction WHERE order_id = ? AND user_id = ? AND order_type = 'Gift Card'
     *                   -> row_array()
     *   The view then pulls in: user (name + billing address), the coupon_checkout row for
     *   the order (drives the uploaded gift card image), orders.status, and order_details rows
     *   with orders.sub_total/discount/grand_total (falling back to a computed subtotal).
     *
     * NOTE: In CI, if no matching transaction row exists, the view renders with undefined
     * array keys (PHP notices, broken output). This port 404s instead when the gift card
     * order isn't found or doesn't belong to the current user.
     */
    public function giftcardInvoice($order_id)
    {
        $user_id = Auth::id();

        $transaction = Transaction::where('order_id', $order_id)
            ->where('user_id', $user_id)
            ->where('order_type', 'Gift Card')
            ->first();

        if (! $transaction) {
            abort(404);
        }

        $order = Order::find($order_id);
        $customer = User::find($transaction->user_id);
        $orderDetails = OrderDetail::where('order_id', $order_id)->get();
        $couponCheckout = CouponCheckout::where('order_id', $order_id)->first();

        // Fallback subtotal from order_details, used when orders.sub_total/discount/grand_total are empty.
        $computedSubtotal = $orderDetails->sum('sub_total');

        return view('dashboard.giftcard_invoice', [
            'title' => 'Invoice',
            'transaction' => $transaction,
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'couponCheckout' => $couponCheckout,
            'computedSubtotal' => $computedSubtotal,
        ]);
    }

    /**
     * Ports CI Dashboard::testimonial().
     *
     * CI logic:
     *   $testimony_datas = testimony_form WHERE customer_id = ? ORDER BY id DESC
     *   $link_add        = site_url('dashboard/testimonial_add')
     */
    public function testimonial()
    {
        $user_id = Auth::id();

        $testimonials = Testimonial::where('customer_id', $user_id)
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.testimonial', [
            'title' => 'Testimonial',
            'testimonials' => $testimonials,
            'link_add' => route('dashboard.testimonial_add'),
        ]);
    }

    /**
     * Ports CI Dashboard::testimonial_add($id = 0).
     *
     * CI logic:
     *   if ($id && !$post)  $post = testimony_form WHERE id = ? -> row_array()
     *   link  = site_url('dashboard/testimonial_validate'.($id ? '/'.$id : ''))
     *   cancel = site_url('dashboard/testimonial')
     *
     * NOTE: In CI, editing any testimonial by id is allowed regardless of ownership.
     * This port restricts both the edit form and the update to testimonials that
     * belong to the authenticated user (404 otherwise).
     */
    public function testimonialAdd($id = 0)
    {
        $testimonial = null;

        if ($id) {
            $testimonial = Testimonial::find($id);

            if (! $testimonial || (int) $testimonial->customer_id !== (int) Auth::id()) {
                abort(404);
            }
        }

        return view('dashboard.testimonial_add', [
            'title' => 'Testimonial',
            'testimonial' => $testimonial,
            'link' => $id
                ? route('dashboard.testimonial_validate', $testimonial->id)
                : route('dashboard.testimonial_validate'),
            'cancel' => route('dashboard.testimonial'),
        ]);
    }

    /**
     * Ports CI Dashboard::testimonial_validate($id = 0).
     *
     * CI logic:
     *   $post = $this->input->post();
     *   if ($id) { update testimony_form SET post WHERE id = $id; }
     *   else     { insert testimony_form (post); }
     *   redirect('dashboard/testimonial');
     *
     * The customer_id is set from the authenticated user server-side rather than
     * trusted from the hidden form field, mirroring CI's behavior (the field is
     * the session user id) while preventing tampering.
     */
    public function testimonialValidate($id, TestimonialRequest $request)
    {
        $post = $request->validated();
        $post['customer_id'] = Auth::id();

        if ($id) {
            $testimonial = Testimonial::find($id);

            if (! $testimonial || (int) $testimonial->customer_id !== (int) Auth::id()) {
                abort(404);
            }

            Testimonial::where('id', $id)->update($post);
        } else {
            Testimonial::create($post);
        }

        return redirect()->route('dashboard.testimonial');
    }
}
