<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ContentPageService;

class BlogController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.blog', array_merge(
            $this->contentPages->listingViewData('Blog'),
            [
                'blogs' => Blog::orderBy('published', 'desc')->get(),
                'latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        // CI stores blog links as "blog/{slug}" and routes on the bare slug.
        return view('frontend.blog_detail', array_merge(
            $this->contentPages->detailViewData(Blog::class, 'blog/'.$link, 'blog_datas'),
            ['latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
