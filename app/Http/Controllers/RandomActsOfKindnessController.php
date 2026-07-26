<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\RandomActsOfKindness;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class RandomActsOfKindnessController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.random_acts_of_kindness', $this->contentPages->listingViewData('Random Acts of Kindness'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.random_acts_of_kindness_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(RandomActsOfKindness::class, $link, 'random_acts_of_kindness_datas')
=======
            $this->contentPages->detailViewData('random_acts_of_kindness', $link, 'random_acts_of_kindness_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
