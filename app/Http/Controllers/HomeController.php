<?php

namespace App\Http\Controllers;

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

        return view('home', compact(
            'title',
            'description',
            'keywords',
            'mainServices',
            'homeTabs',
            'mainSliders',
        ));
    }
}