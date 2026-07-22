<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;
use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(
        private NewsService $news,
        private ContentPageService $contentPages
    ) {
    }

    public function list(int $start = 0)
    {
        $data = array_merge(
            $this->news->paginatedList($start),
            $this->contentPages->listingViewData('News'),
            ['pagination' => '']
        );

        return view('frontend.news', $data);
    }

    public function detail(string $link)
    {
        return view(
            'frontend.news_detail',
            $this->contentPages->detailViewData('news', $link, 'news_datas')
        );
    }
}
