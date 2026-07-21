<?php

namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $data['title'] = 'Cart';
        $data['description'] = '';
        $data['keywords'] = '';
    $data['cartItems'] = $cartItems;
        return view('frontend.cart', $data);
    }

    // AJAX – update quantity
    public function updateItemQty($rowid, $qty)
    {
        $cart = session()->get('cart', []);
        $update = 0;
        if (isset($cart[$rowid])) {
            $cart[$rowid]['qty'] = (int)$qty;
            session()->put('cart', $cart);
            $update = 1;
        }
        return response($update ? 'ok' : 'err');
    }

    // AJAX – apply coupon
    public function couponCode($code)
    {
        $coupon = DB::table('coupon_checkout')
            ->where('coupon_number', $code)
            ->where('status', 0)
            ->first();

        if ($coupon) {
            $gift = DB::table('gift_card')->where('id', $coupon->gift_card_id)->first();
            if ($gift) {
                session()->put('coupon_code', $coupon->coupon_number);
                session()->put('discount_value', $gift->price);
                return 'ok';
            }
        }
        return 'err';
    }

    // Remove item
    public function removeItem($rowid)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$rowid])) {
            unset($cart[$rowid]);
            $cart = array_values($cart); // re-index
            session()->put('cart', $cart);
        }
        return redirect('/cart');
    }
}