<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Services\ContentPageService;

class PageController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function show(string $pageLink)
    {
        return view('frontend.pages', array_merge(
            $this->contentPages->detailViewData(Page::class, $pageLink, 'page_datas'),
            ['latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
