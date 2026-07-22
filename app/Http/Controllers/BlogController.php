<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class BlogController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.blog', $this->contentPages->listingViewData('Blog'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.blog_detail',
            $this->contentPages->detailViewData('blog', $link, 'blog_datas')
        );
    }
}
