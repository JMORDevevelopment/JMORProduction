<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function xml()
    {
        $lastmod = date(DATE_ATOM, time());

        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlString .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $mainNav = Menu::where('parent_id', 0)->orderBy('id', 'asc')->get();
        foreach ($mainNav as $menuItem) {
            $xmlString .= '<url>';
            $xmlString .= '<loc>'.url(htmlentities($menuItem->url)).'</loc>';
            $xmlString .= '<lastmod>'.$lastmod.'</lastmod>';
            $xmlString .= '<priority>1.00</priority>';
            $xmlString .= '</url>';

            $subItems = Menu::where('parent_id', $menuItem->id)->orderBy('position', 'asc')->get();
            foreach ($subItems as $subItem) {
                $xmlString .= '<url>';
                $xmlString .= '<loc>'.url(htmlentities($subItem->url)).'</loc>';
                $xmlString .= '<lastmod>'.$lastmod.'</lastmod>';
                $xmlString .= '<priority>0.80</priority>';
                $xmlString .= '</url>';

                $subChildren = Menu::where('parent_id', $subItem->id)->orderBy('id', 'asc')->get();
                foreach ($subChildren as $subChild) {
                    $xmlString .= '<url>';
                    $xmlString .= '<loc>'.url(htmlentities($subChild->url)).'</loc>';
                    $xmlString .= '<lastmod>'.$lastmod.'</lastmod>';
                    $xmlString .= '<priority>0.64</priority>';
                    $xmlString .= '</url>';

                    $deepItems = Menu::where('parent_id', $subChild->id)->orderBy('id', 'asc')->get();
                    foreach ($deepItems as $deepItem) {
                        $xmlString .= '<url>';
                        $xmlString .= '<loc>'.url(htmlentities($deepItem->url)).'</loc>';
                        $xmlString .= '<lastmod>'.$lastmod.'</lastmod>';
                        $xmlString .= '<priority>0.64</priority>';
                        $xmlString .= '</url>';
                    }
                }
            }
        }

        $xmlString .= '</urlset>';

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlString);

        return response('Wrote: '.$dom->save(public_path('sitemap.xml')).' bytes');
    }

    public function ping()
    {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(5)
                ->get('http://www.google.com/ping?sitemap='.url('sitemap.xml'));

            if ($response->successful()) {
                return response('success');
            }
        } catch (\Exception $e) {
            Log::error('Sitemap ping failed: '.$e->getMessage());
        }

        return response('try again');
    }
}
