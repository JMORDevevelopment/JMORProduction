<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Page;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class PageController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function show(string $pageLink)
    {
        return view(
            'frontend.pages',
<<<<<<< HEAD
            $this->contentPages->detailViewData(Page::class, $pageLink, 'page_datas')
=======
            $this->contentPages->detailViewData('pages', $pageLink, 'page_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
