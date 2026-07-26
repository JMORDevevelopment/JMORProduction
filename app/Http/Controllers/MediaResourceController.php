<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\MediaResource;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class MediaResourceController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.media_resources', $this->contentPages->listingViewData('Media Resources'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.media_resources_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(MediaResource::class, $link, 'media_resouces_datas')
=======
            $this->contentPages->detailViewData('media_resouces', $link, 'media_resouces_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
