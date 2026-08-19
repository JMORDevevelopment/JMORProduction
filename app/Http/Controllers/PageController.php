<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Services\ContentPageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function show(Request $request)
    {
        $link = trim($request->path(), '/');

        return view('frontend.page', array_merge(
            $this->contentPages->detailViewData(Page::class, $link, 'page_datas'),
            ['latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
