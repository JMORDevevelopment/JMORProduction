<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\ContentPageService;

class PageController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function show(string $pageLink)
    {
        return view(
            'frontend.pages',
            $this->contentPages->detailViewData(Page::class, $pageLink, 'page_datas')
        );
    }
}
