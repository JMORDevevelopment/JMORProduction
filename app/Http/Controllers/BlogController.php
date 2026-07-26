<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Blog;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
            $this->contentPages->detailViewData(Blog::class, $link, 'blog_datas')
=======
            $this->contentPages->detailViewData('blog', $link, 'blog_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
