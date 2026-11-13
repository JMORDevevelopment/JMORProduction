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
        $homeTabs = HomeTab::orderBy('tab_id', 'asc')->get()
            ->filter(fn (HomeTab $homeTab): bool => $this->isPublishablePlan($homeTab))
            ->values()
            ->toArray();
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

    /**
     * A service plan may only be published with an approved title, a price and
     * at least one inclusion. Draft or test rows in the CMS (for example an
     * "asd" tab priced "12") would otherwise be advertised on the homepage.
     */
    private function isPublishablePlan(HomeTab $homeTab): bool
    {
        $costs = array_filter(
            (array) $homeTab->cost,
            fn ($cost): bool => trim(strip_tags((string) $cost)) !== ''
        );

        $inclusions = array_filter(
            (array) $homeTab->tab_list,
            fn ($inclusion): bool => trim((string) $inclusion) !== ''
        );

        $hasPrice = collect($costs)->contains(
            fn ($cost): bool => preg_match('/\$\s?\d/', (string) $cost) === 1
        );

        return filled($homeTab->tab_title) && $hasPrice && $inclusions !== [];
    }
}
