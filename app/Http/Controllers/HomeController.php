<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\HomeTab;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $title = Setting::get('meta_title', '');
        $description = Setting::get('meta_description', '');
        $keywords = Setting::get('meta_keyword', '');

        $mainServices = Service::all()->toArray();
        $homeTabs = HomeTab::orderBy('tab_id', 'asc')->get()->toArray();
        $mainSliders = Slider::orderBy('priority', 'asc')->get()->toArray();

        // The homepage feed only shows the newest main blog articles (no other
        // content types), capped so the section stays a teaser for /blog.
        $latestBlogPosts = Blog::query()
            ->whereNotNull('published')
            ->where('published', '<=', now())
            ->orderByDesc('published')
            ->limit(3)
            ->get();

        // The compact homepage inquiry form posts through the contact endpoint,
        // so its protection challenge is seeded the same way (036).
        $randomNumber1 = mt_rand(1, 15);
        $randomNumber2 = mt_rand(1, 15);
        $phone = Setting::get('phone', '');
        session(['captcha_numbers' => [$randomNumber1, $randomNumber2]]);

        return view('frontend.home', compact(
            'title',
            'description',
            'keywords',
            'mainServices',
            'homeTabs',
            'mainSliders',
            'latestBlogPosts',
            'randomNumber1',
            'randomNumber2',
            'phone'
        ));
    }
}
