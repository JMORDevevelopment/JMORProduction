<?php

use App\Models\BrandGuideline;
use App\Models\CaseStudy;
use App\Models\CategoryRadioShow;
use App\Models\Event;
use App\Models\GiftCard;
use App\Models\MediaResource;
use App\Models\News;
use App\Models\Page;
use App\Models\PressRelease;
use App\Models\RadioShow;
use App\Models\RandomActsOfKindness;
use App\Models\Recommended;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    Event::create([
        'name' => 'Networking Event',
        'link' => 'networking-event',
        'description' => '<p>Event description content</p>',
        'image' => 'uploads/events/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    CaseStudy::create([
        'name' => 'Production Client Case Study',
        'link' => 'production-client-case-study',
        'description' => '<p>Case study description content</p>',
        'image' => 'uploads/case_studies/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    MediaResource::create([
        'name' => 'JMOR In The Spotlight',
        'link' => 'jmor-in-the-spotlight',
        'description' => '<p>Media resource description content</p>',
        'image' => 'uploads/media_resouces/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    PressRelease::create([
        'name' => 'Tech Expert YouTube Channel',
        'link' => 'tech-expert-youtube-channel',
        'description' => '<p>Press release description content</p>',
        'image' => 'uploads/press_releases/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    BrandGuideline::create([
        'name' => 'JMOR Brand Guidelines',
        'link' => 'jmor-brand-guidelines',
        'description' => '<p>Brand guideline description content</p>',
        'image' => 'uploads/brand_guidelines/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    Recommended::create([
        'name' => 'The Dyson V7',
        'link' => 'the-dyson-v7',
        'description' => '<p>Recommended description content</p>',
        'image' => 'uploads/recommended/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    RandomActsOfKindness::create([
        'name' => 'Random Act of Kindness',
        'link' => 'random-acts-of-kindness',
        'description' => '<p>Random act description content</p>',
        'image' => 'uploads/random_acts_of_kindness/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    News::create([
        'name' => 'JMOR News Item',
        'link' => 'jmor-news-item',
        'priority' => 1,
        'type' => 'general',
        'description' => '<p>News description content</p>',
        'image' => 'uploads/news/test.jpg',
        'published' => '2020-01-01 00:00:00',
    ]);

    $category = CategoryRadioShow::create([
        'title' => 'JMOR Tech Talk Show',
        'menu_status' => 1,
        'parent_id' => 0,
        'sub_title' => '',
        'description' => '',
        'image' => 'uploads/category_radio_show/test.jpg',
        'link' => 'jmor-tech-talk-show',
        'published' => '2020-01-01 00:00:00',
    ]);

    RadioShow::create([
        'name' => 'Tech Talk Show Episode 1',
        'link' => 'tech-talk-show-episode-1',
        'description' => '<p>Radio show description content</p>',
        'show_date' => '2020-06-15',
        'category_id' => $category->id,
        'image' => 'uploads/radio_show/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    Service::create([
        'title' => 'Managed IT Services',
        'description' => '<p>Service description content</p>',
        'image' => 'uploads/service/test.jpg',
        'meta_title' => '',
        'meta_description' => '',
        'keywords' => '',
        'link' => 'managed-it-services',
    ]);

    Page::create([
        'name' => 'About Us',
        'link' => 'about-us',
        'priority' => 1,
        'slider_status' => 0,
        'menu_location' => 1,
        'description' => '<p>About us page description content</p>',
        'image' => '',
        'form_id' => 0,
        'menu_status' => 0,
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    GiftCard::create([
        'link' => 'gift-card',
        'name' => 'Gift Card',
        'heading' => 'Gift Card',
        'description' => '<p>Gift card description</p>',
        'image' => '',
        'price' => '25',
        'upfront' => '',
        'category' => '',
        'coupon_number' => null,
        'status' => 1,
    ]);

    Testimonial::create([
        'customer_id' => 1,
        'service_used' => 'Managed IT Services',
        'message' => 'Great support team!',
        'status' => 1,
    ]);
});

test('content listing pages render their posts', function () {
    $this->get(route('events'))->assertSuccessful()->assertSee('Networking Event');
    $this->get(route('case-studies'))->assertSuccessful()->assertSee('Production Client Case Study');
    $this->get(route('media-resources'))->assertSuccessful()->assertSee('JMOR In The Spotlight');
    $this->get(route('press-releases'))->assertSuccessful()->assertSee('Tech Expert YouTube Channel');
    $this->get(route('brand-guidelines'))->assertSuccessful()->assertSee('JMOR Brand Guidelines');
    $this->get(route('recommended'))->assertSuccessful()->assertSee('The Dyson V7');
    $this->get(route('random-acts-of-kindness'))->assertSuccessful()->assertSee('Random Act of Kindness');
    $this->get(route('jmor-shows'))->assertSuccessful()->assertSee('Tech Talk Show Episode 1');
    $this->get(route('news'))->assertSuccessful()->assertSee('JMOR News Item');
    $this->get(route('services'))->assertSuccessful()->assertSee('Managed IT Services');
    $this->get(route('gift-card'))->assertSuccessful()->assertSee('Gift Card');
    $this->get(route('testimonials'))->assertSuccessful()->assertSee('Great support team!');
});

test('content detail pages render their post by link', function () {
    $this->get(route('events.detail', 'networking-event'))->assertSuccessful()->assertSee('Event description content');
    $this->get(route('case-studies.detail', 'production-client-case-study'))->assertSuccessful()->assertSee('Case study description content');
    $this->get(route('media-resources.detail', 'jmor-in-the-spotlight'))->assertSuccessful()->assertSee('Media resource description content');
    $this->get(route('press-releases.detail', 'tech-expert-youtube-channel'))->assertSuccessful()->assertSee('Press release description content');
    $this->get(route('brand-guidelines.detail', 'jmor-brand-guidelines'))->assertSuccessful()->assertSee('Brand guideline description content');
    $this->get(route('recommended.detail', 'the-dyson-v7'))->assertSuccessful()->assertSee('Recommended description content');
    $this->get(route('random-acts-of-kindness.detail', 'random-acts-of-kindness'))->assertSuccessful()->assertSee('Random act description content');
    $this->get(route('jmor-shows.detail', 'tech-talk-show-episode-1'))->assertSuccessful()->assertSee('Radio show description content');
    $this->get(route('news.detail', 'jmor-news-item'))->assertSuccessful()->assertSee('News description content');
    $this->get(route('service.detail', 'managed-it-services'))->assertSuccessful()->assertSee('Service description content');
});

test('db pages render by link and unknown pages return 404', function () {
    $this->get(route('about-us'))->assertSuccessful()->assertSee('About us page description content');
    $this->get(route('pages.show', 'does-not-exist'))->assertNotFound();
});

test('a radio show category page renders its shows', function () {
    $this->get(route('category-jmor-shows', 'jmor-tech-talk-show'))
        ->assertSuccessful()
        ->assertSee('JMOR Tech Talk Show')
        ->assertSee('Tech Talk Show Episode 1');
});

test('the search page returns matching content', function () {
    $this->post(route('search'), ['search' => 'About'])->assertSuccessful()->assertSee('About Us');
    $this->post(route('search'), ['search' => 'nothing-matches'])->assertSuccessful()->assertSee('Nothing Record Found');
});
