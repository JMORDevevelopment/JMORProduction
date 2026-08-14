<?php

use App\Models\CaseStudy;
use App\Models\Event;

beforeEach(function () {
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
});

test('the events listing page shows its posts', function () {
    $this->get(route('events'))
        ->assertSuccessful()
        ->assertSee('Networking Event');
});

test('the events detail page renders its post by link', function () {
    $this->get(route('events.detail', 'networking-event'))
        ->assertSuccessful()
        ->assertSee('Event description content');
});

test('the case studies listing page shows its posts', function () {
    $this->get(route('case-studies'))
        ->assertSuccessful()
        ->assertSee('Production Client Case Study');
});

test('the case studies detail page renders its post by link', function () {
    $this->get(route('case-studies.detail', 'production-client-case-study'))
        ->assertSuccessful()
        ->assertSee('Case study description content');
});

test('unknown event and case study links return a 404', function () {
    $this->get(route('events.detail', 'does-not-exist'))->assertNotFound();
    $this->get(route('case-studies.detail', 'does-not-exist'))->assertNotFound();
});
