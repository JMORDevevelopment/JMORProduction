<?php

namespace App\Http\Controllers;

use App\Models\Page;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('name', 'asc')->get();

        return view('frontend.sitemap', [
            'title' => 'Sitemap',
            'description' => '',
            'keywords' => '',
            'pages' => $pages,
        ]);
    }
}
