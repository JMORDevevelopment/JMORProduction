<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\PressRelease;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class PressReleaseController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.press_releases', $this->contentPages->listingViewData('Press Releases'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.press_releases_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(PressRelease::class, $link, 'press_releases_datas')
=======
            $this->contentPages->detailViewData('press_releases', $link, 'press_releases_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
