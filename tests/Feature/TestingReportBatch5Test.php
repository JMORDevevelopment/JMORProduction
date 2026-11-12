<?php

use App\Models\Blog;
use App\Models\CategoryRadioShow;
use App\Models\RadioShow;
use App\Models\RandomActsOfKindness;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    DB::table('settings')->insert([
        ['option' => 'email', 'value' => 'admin@test.com'],
        ['option' => 'phone', 'value' => '(877) 767-5667'],
    ]);
});

function batch5Blog(string $name, string $slug, string $published): Blog
{
    return Blog::create([
        'name' => $name,
        'link' => 'blog/'.$slug,
        'description' => '<p>'.$name.' body copy</p>',
        'image' => 'uploads/blog/'.$slug.'.jpg',
        'published' => $published,
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);
}

test('the homepage shows only the newest three blog posts with an explore more link', function () {
    batch5Blog('Oldest Article', 'oldest-article', '2020-01-01 00:00:00');
    batch5Blog('Third Article', 'third-article', '2021-01-01 00:00:00');
    batch5Blog('Second Article', 'second-article', '2022-01-01 00:00:00');
    batch5Blog('Newest Article', 'newest-article', '2023-01-01 00:00:00');

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Newest Article')
        ->assertSee('Second Article')
        ->assertSee('Third Article')
        ->assertDontSee('Oldest Article')
        ->assertSee('Explore More', false);
});

test('the homepage blog feed skips posts scheduled in the future', function () {
    batch5Blog('Published Article', 'published-article', '2023-01-01 00:00:00');
    batch5Blog('Scheduled Article', 'scheduled-article', now()->addYear()->toDateTimeString());

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Published Article')
        ->assertDontSee('Scheduled Article');
});

test('informational homepage headings are not wrapped in links', function () {
    batch5Blog('Feed Article', 'feed-article', '2023-01-01 00:00:00');

    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    // A heading is informational; only CTA elements may be anchors (issue 001).
    expect($html)->not->toMatch('/<a\b[^>]*>\s*<h[1-2]\b/i');
    expect($html)->not->toMatch('/<h[1-2]\b[^>]*>\s*<a\b/i');
});

test('the homepage lead form posts through the contact endpoint and stores the lead', function () {
    $this->get(route('home'))->assertSuccessful()->assertSee('name="protection_question"', false);

    $captcha = session('captcha_numbers');
    expect($captcha)->toBeArray()->toHaveCount(2);

    $this->post(route('contact.submit'), [
        'name' => 'Home Lead',
        'email' => 'lead@example.com',
        'phone' => '(201) 555-0123',
        'reason' => 'General Inquiry',
        'message' => 'Our network keeps dropping.',
        'protection_question' => $captcha[0] + $captcha[1],
    ])->assertRedirect(route('contact'));

    $this->assertDatabaseHas('contact_us', [
        'name' => 'Home Lead',
        'email' => 'lead@example.com',
        'reason' => 'General Inquiry',
    ]);
});

test('the homepage links a telephone call to action for mobile visitors', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('href="tel:8777675667"', false);
});

test('guest appearances render as their own feed and stay out of the blog', function () {
    $category = CategoryRadioShow::create([
        'title' => 'Shows I Appeared as a Guest On',
        'sub_title' => 'Guest appearances',
        'description' => 'Shows JMOR appeared on as a guest.',
        'link' => 'shows-i-appeared-as-a-guest-on',
        'published' => '2020-01-01 00:00:00',
        'parent_id' => 0,
    ]);

    RadioShow::create([
        'name' => 'Guest Spot On Another Podcast',
        'link' => 'guest-spot-on-another-podcast',
        'description' => 'A guest appearance',
        'show_date' => '2023-05-01',
        'category_id' => $category->id,
        'image' => 'uploads/radio_show/guest.jpg',
        'published' => '2023-05-01 00:00:00',
        'meta_title' => 'Guest Spot',
        'meta_description' => 'Guest spot description',
        'meta_keywords' => 'guest',
    ]);

    $this->get(route('guest-appearances'))
        ->assertSuccessful()
        ->assertSee('Guest Spot On Another Podcast');

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertDontSee('Guest Spot On Another Podcast');
});

test('random acts of kindness is an independent content type from the blog', function () {
    batch5Blog('Blog Article', 'blog-article', '2023-01-01 00:00:00');

    RandomActsOfKindness::create([
        'name' => 'Surprise Groceries For A Neighbour',
        'link' => 'surprise-groceries-for-a-neighbour',
        'description' => '<p>A kind act</p>',
        'image' => 'uploads/rak/kindness.jpg',
        'published' => '2023-02-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $this->get(route('random-acts-of-kindness'))
        ->assertSuccessful()
        ->assertSee('Surprise Groceries For A Neighbour')
        ->assertDontSee('Blog Article');

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('Blog Article')
        ->assertDontSee('Surprise Groceries For A Neighbour');
});

test('the duplicated tech talk show urls permanently redirect to the show category', function () {
    $this->get('/jmor-tech-talk-cast')
        ->assertStatus(301)
        ->assertRedirect('/category-jmor-shows/jmor-tech-talk-show');

    $this->get('/jmor-tech-talk-show')
        ->assertStatus(301)
        ->assertRedirect('/category-jmor-shows/jmor-tech-talk-show');
});

test('the contact page prioritises acquisition pathways with distinct destinations', function () {
    CategoryRadioShow::create([
        'title' => 'JMOR Tech Talk Show',
        'sub_title' => 'The show',
        'description' => 'JMOR Tech Talk Show episodes.',
        'link' => 'jmor-tech-talk-show',
        'published' => '2020-01-01 00:00:00',
        'parent_id' => 0,
    ]);

    $this->get(route('contact'))
        ->assertSuccessful()
        // Technology assessment is its own destination.
        ->assertSee('href="'.route('request-information').'"', false)
        // Direct telephone link instead of a reveal toggle.
        ->assertSee('href="tel:8777675667"', false)
        // Guest application opens the interview form.
        ->assertSee('onclick="inquiryformtwo()"', false)
        // Conversion tracking hooks are present.
        ->assertSee('data-conversion="technology_assessment"', false)
        ->assertSee('data-conversion="web_inquiry"', false);
});

test('the contact page confirms a successful submission', function () {
    $this->get(route('contact'));
    $captcha = session('captcha_numbers');

    $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '555-1234',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'protection_question' => $captcha[0] + $captcha[1],
    ])->assertSessionHas('contact_success');

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('role="alert"', false)
        ->assertSee('your message was sent', false);
});
