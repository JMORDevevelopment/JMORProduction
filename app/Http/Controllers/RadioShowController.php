<?php

namespace App\Http\Controllers;

use App\Models\CategoryRadioShow;
use App\Models\RadioShow;
use App\Services\ContentPageService;
use App\Services\RadioShowService;
use Illuminate\Http\Request;

class RadioShowController extends Controller
{
    public function __construct(
        private RadioShowService $radioShows,
        private ContentPageService $contentPages
    ) {}

    public function posts()
    {
        return view('frontend.jmor_radio', array_merge(
            $this->contentPages->listingViewData('Jmor Shows'),
            [
                'shows' => RadioShow::orderBy('id', 'desc')->get(),
                'latestShows' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.jmor_radio_detail', array_merge(
            $this->contentPages->detailViewData(RadioShow::class, $link, 'jmor_radio_datas'),
            [
                'latestShows' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function category(string $categoryLink, ?string $year = null)
    {
        return view('frontend.category_radio_show', array_merge(
            $this->radioShows->categoryViewData($categoryLink, $year),
            ['categories' => CategoryRadioShow::orderBy('id', 'asc')->get()]
        ));
    }

    public function categoriesList(Request $request)
    {
        $year = $request->post('year', date('Y'));
        $endDate = $year.'-12-31';

        $categories = CategoryRadioShow::where('published', '<=', $endDate)
            ->where('parent_id', 0)
            ->orderBy('id', 'asc')
            ->get();

        if ($categories->isEmpty()) {
            $payload = ['msg' => 'success', 'data' => 'No Record Found.'];
        } else {
            $html = '';
            foreach ($categories as $category) {
                $link = route('category-jmor-shows.year', [
                    'category' => $category->link,
                    'year' => $year,
                ]);
                $title = e($category->title);
                $html .= '<div class="">
                <div class="">
                    <div class="col-8">
                        <h6 class="my-2 font-size-14"><a href="'.$link.'">'.$title.'</a>
						</h6>
                    </div>
                </div>
            </div>';
            }
            $payload = ['msg' => 'success', 'data' => $html];
        }

        // The original controller echoed a plain text/html JSON string, and the
        // radio view calls JSON.parse() on the raw response, so do not return a
        // JsonResponse here (jQuery would auto-parse it and JSON.parse would fail).
        return response(json_encode($payload), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
