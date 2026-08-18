<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use App\Services\ContentPageService;

class PressReleaseController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.press_releases', array_merge(
            $this->contentPages->listingViewData('Press Releases'),
            [
                'posts' => PressRelease::orderBy('id', 'desc')->get(),
                'latestPosts' => PressRelease::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.press_releases_detail', array_merge(
            $this->contentPages->detailViewData(PressRelease::class, $link, 'press_releases_datas'),
            ['latestPosts' => PressRelease::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
