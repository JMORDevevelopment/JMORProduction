<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Service;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class ServicePageController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function list()
    {
        return view('frontend.service', $this->contentPages->listingViewData('Services'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.service_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(Service::class, $link, 'service_datas')
=======
            $this->contentPages->detailViewData('service', $link, 'service_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
