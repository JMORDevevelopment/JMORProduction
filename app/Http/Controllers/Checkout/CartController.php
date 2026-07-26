<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\GiftCard;
use App\Services\CartService;
use App\Services\PackageService;
use Illuminate\Http\Request;
=======
use App\Services\CartService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private PackageService $packages
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return view('frontend.cart')->with('message', 'Your cart is empty.');
        }

        $cartItems = $this->cart->items();

        return view('frontend.cart', compact('cartItems'));
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
<<<<<<< HEAD
        $gift = GiftCard::where('id', $giftId)->first();
=======
        $gift = DB::table('gift_card')->where('id', $giftId)->first();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if (! $gift) {
            return redirect('/cart');
        }

        $this->cart->markGiftCard($giftId);

        $this->cart->replace([[
            'id' => $gift->id . 'gc',
            'qty' => 1,
            'type' => 'Gift Card',
            'price' => $gift->price,
            'name' => $gift->name,
            'description' => $gift->description,
        ]]);

        return redirect('/cart');
    }
}