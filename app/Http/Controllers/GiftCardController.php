<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class GiftCardController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function list()
    {
        return view('frontend.gift_card', $this->contentPages->listingViewData('Gift Card'));
    }
}
