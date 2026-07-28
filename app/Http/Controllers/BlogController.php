<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ContentPageService;

class BlogController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.blog', $this->contentPages->listingViewData('Blog'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.blog_detail',
            $this->contentPages->detailViewData(Blog::class, $link, 'blog_datas')
        );
    }
}
