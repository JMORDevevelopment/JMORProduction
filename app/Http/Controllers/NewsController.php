<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\News;
use App\Services\ContentPageService;
use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(
        private NewsService $news,
        private ContentPageService $contentPages
    ) {}

    public function list(int $start = 0)
    {
        $data = array_merge(
            $this->news->paginatedList($start),
            $this->contentPages->listingViewData('News'),
            ['latestPosts' => News::orderBy('news_id', 'desc')->limit(5)->get()]
        );

        return view('frontend.news', $data);
    }

    public function detail(string $link)
    {
        return view('frontend.news_detail', array_merge(
            $this->contentPages->detailViewData(News::class, $link, 'news_datas'),
            ['latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
