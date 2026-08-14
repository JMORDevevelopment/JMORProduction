<?php

namespace App\Http\Controllers;

use App\Models\CategoryRadioShow;
use App\Models\RadioShow;
use App\Services\ContentPageService;
use App\Services\RadioShowService;
use Illuminate\Http\JsonResponse;
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
                'posts' => RadioShow::orderBy('id', 'desc')->get(),
                'latestPosts' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.jmor_radio_detail', array_merge(
            $this->contentPages->detailViewData(RadioShow::class, $link, 'jmor_radio_datas'),
            [
                'latestPosts' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function category(string $categoryLink, ?string $year = null)
    {
        return view(
            'frontend.category_radio_show',
            $this->radioShows->categoryViewData($categoryLink, $year)
        );
    }

    public function categoriesList(Request $request): JsonResponse
    {
        $year = $request->input('year', date('Y'));
        $endDate = $year.'-12-31';

        $categories = CategoryRadioShow::where('published', '<=', $endDate)
            ->where('parent_id', 0)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'msg' => 'success',
            'data' => view('frontend.radio_categories', [
                'categories' => $categories,
                'year' => $year,
            ])->render(),
        ]);
    }
}
