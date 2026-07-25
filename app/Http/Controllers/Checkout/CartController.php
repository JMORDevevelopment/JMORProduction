<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private PackageService $packages
    ) {
    }

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
            $cart[$rowid]['qty'] = (int) $qty;
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

    public function addPackages(Request $request)
    {
        $this->cart->resetForPackagePurchase();

        $result = $this->packages->buildCartLines(
            (int) $request->input('package_id'),
            $request->input('server_qty'),
            $request->input('system_qty'),
            $request->input('package_type')
        );

        if (empty($result['lines'])) {
            return redirect('/cart');
        }

        $this->cart->addLines($result['lines']);
        $this->cart->setCheckoutType($result['checkoutType']);

        return redirect('/cart');
    }

    public function addGiftCard(Request $request)
    {
        $this->cart->resetForGiftCardPurchase();

        $giftId = $request->input('gift_id');
        $gift = DB::table('gift_card')->where('id', $giftId)->first();

        if (! $gift) {
            return redirect('/cart');
        }

        $this->cart->markGiftCard($giftId);
        $this->cart->replace([[
            'id' => $gift->id.'gc',
            'qty' => 1,
            'type' => 'Gift Card',
            'price' => $gift->price,
            'name' => $gift->name,
            'description' => $gift->description,
        ]]);

        return redirect('/cart');
    }
}