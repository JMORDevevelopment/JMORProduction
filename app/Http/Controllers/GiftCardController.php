<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Services\ContentPageService;

class GiftCardController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function list()
    {
        return view('frontend.gift_card', array_merge(
            $this->contentPages->listingViewData('Gift Card'),
            [
                'giftCards' => GiftCard::orderBy('id', 'desc')->get(),
            ]
        ));
    }
}
