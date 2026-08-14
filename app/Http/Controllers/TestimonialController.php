<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Services\ContentPageService;

class TestimonialController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function index()
    {
        return view('frontend.testimonials', array_merge(
            $this->contentPages->listingViewData('Testimonials'),
            ['testimonials' => Testimonial::where('status', 1)->orderBy('id', 'desc')->get()]
        ));
    }
}
