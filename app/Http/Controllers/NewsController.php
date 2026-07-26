<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\News;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
            $this->contentPages->detailViewData(News::class, $link, 'news_datas')
=======
            $this->contentPages->detailViewData('news', $link, 'news_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
