<?php

use App\Filament\Admin\Resources\BlogResource\Pages\CreateBlog;
use App\Filament\Admin\Resources\BlogResource\Pages\EditBlog;
use App\Filament\Admin\Resources\BlogResource\Pages\ListBlogs;
use App\Filament\Admin\Resources\BrandGuidelineResource\Pages\CreateBrandGuideline;
use App\Filament\Admin\Resources\CaseStudyResource\Pages\CreateCaseStudy;
use App\Filament\Admin\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Admin\Resources\MediaResourceResource\Pages\CreateMediaResource;
use App\Filament\Admin\Resources\NewsResource\Pages\CreateNews;
use App\Filament\Admin\Resources\PressReleaseResource\Pages\CreatePressRelease;
use App\Filament\Admin\Resources\RandomActsResource\Pages\CreateRandomActs;
use App\Filament\Admin\Resources\RecommendedResource\Pages\CreateRecommended;
use App\Models\Admin;
use App\Models\Blog;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Test',
        'lastname' => 'Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($this->admin, 'admin');
});

/**
 * Helper to set Filament form data on a Livewire component.
 * Skips 'image' key since FileUpload expects array/UploadedFile.
 */
function setFormData($component, array $data)
{
    foreach ($data as $key => $value) {
        // FileUpload components expect arrays
        if ($key === 'image' && is_string($value)) {
            $value = [$value];
        }
        $component->set("data.{$key}", $value);
    }

    return $component;
}

// ─── Blog List ─────────────────────────────────────────────────────────

test('admin can access blog list page', function () {
    $this->get('/admin/blogs')->assertSuccessful();
});

test('blog list page shows existing posts', function () {
    Blog::create([
        'name' => 'Existing Post',
        'link' => 'blog/existing-post',
        'description' => '<p>Content</p>',
        'image' => 'uploads/blog/test.jpg',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ]);

    Livewire::test(ListBlogs::class)
        ->assertCanRenderTableColumn('name');
});

// ─── Blog Create ───────────────────────────────────────────────────────

test('admin can access blog create page', function () {
    $this->get('/admin/blogs/create')->assertSuccessful();
});

test('admin can create a blog post via livewire', function () {
    setFormData(Livewire::test(CreateBlog::class), [
        'name' => 'Test Blog Post',
        'image' => 'uploads/blog/test.jpg',
        'description' => '<p>Test content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test Meta',
        'meta_keywords' => 'test, blog',
        'meta_description' => 'Test description',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('blog', [
        'name' => 'Test Blog Post',
        'link' => 'blog/test-blog-post',
    ]);
});

test('blog slug auto-generates from title', function () {
    setFormData(Livewire::test(CreateBlog::class), [
        'name' => 'My Awesome Blog Post',
        'image' => 'uploads/blog/test.jpg',
        'description' => '<p>Content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('blog', [
        'name' => 'My Awesome Blog Post',
        'link' => 'blog/my-awesome-blog-post',
    ]);
});

// ─── Blog Edit ─────────────────────────────────────────────────────────

test('admin can access blog edit page', function () {
    $blog = Blog::create([
        'name' => 'Existing Post',
        'link' => 'blog/existing-post',
        'description' => '<p>Content</p>',
        'image' => 'uploads/blog/test.jpg',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ]);

    $this->get("/admin/blogs/{$blog->id}/edit")->assertSuccessful();
});

test('admin can update a blog post via livewire', function () {
    $blog = Blog::create([
        'name' => 'Old Title',
        'link' => 'blog/old-title',
        'description' => '<p>Old content</p>',
        'image' => 'uploads/blog/test.jpg',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ]);

    setFormData(Livewire::test(EditBlog::class, ['record' => $blog->id]), [
        'name' => 'Updated Title',
        'image' => 'uploads/blog/test.jpg',
        'description' => '<p>Updated content</p>',
        'published' => '2026-06-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('blog', [
        'id' => $blog->id,
        'name' => 'Updated Title',
    ]);
});

// ─── Blog Delete ───────────────────────────────────────────────────────

test('admin can delete a blog post', function () {
    $blog = Blog::create([
        'name' => 'To Delete',
        'link' => 'blog/to-delete',
        'description' => '<p>Content</p>',
        'image' => 'uploads/blog/test.jpg',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ]);

    Livewire::test(ListBlogs::class)
        ->callTableAction('delete', $blog);

    $this->assertDatabaseMissing('blog', ['id' => $blog->id]);
});

// ─── Events ────────────────────────────────────────────────────────────

test('admin can access events list page', function () {
    $this->get('/admin/events')->assertSuccessful();
});

test('admin can create an event via livewire', function () {
    setFormData(Livewire::test(CreateEvent::class), [
        'name' => 'Test Event',
        'image' => 'uploads/events/test.jpg',
        'description' => '<p>Event content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('events', ['name' => 'Test Event']);
});

// ─── Case Studies ──────────────────────────────────────────────────────

test('admin can access case studies list page', function () {
    $this->get('/admin/case-studies')->assertSuccessful();
});

test('admin can create a case study via livewire', function () {
    setFormData(Livewire::test(CreateCaseStudy::class), [
        'name' => 'Test Case Study',
        'image' => 'uploads/case-studies/test.jpg',
        'description' => '<p>Case study content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('case_studies', ['name' => 'Test Case Study']);
});

// ─── Press Releases ────────────────────────────────────────────────────

test('admin can access press releases list page', function () {
    $this->get('/admin/press-releases')->assertSuccessful();
});

test('admin can create a press release via livewire', function () {
    setFormData(Livewire::test(CreatePressRelease::class), [
        'name' => 'Test Press Release',
        'image' => 'uploads/press-releases/test.jpg',
        'description' => '<p>Press release content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('press_releases', ['name' => 'Test Press Release']);
});

// ─── Media Resources ───────────────────────────────────────────────────

test('admin can access media resources list page', function () {
    $this->get('/admin/media-resources')->assertSuccessful();
});

test('admin can create a media resource via livewire', function () {
    setFormData(Livewire::test(CreateMediaResource::class), [
        'name' => 'Test Media Resource',
        'image' => 'uploads/media-resources/test.jpg',
        'description' => '<p>Media resource content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('media_resouces', ['name' => 'Test Media Resource']);
});

// ─── Recommended ───────────────────────────────────────────────────────

test('admin can access recommended list page', function () {
    $this->get('/admin/recommended')->assertSuccessful();
});

test('admin can create a recommended item via livewire', function () {
    setFormData(Livewire::test(CreateRecommended::class), [
        'name' => 'Test Recommended',
        'image' => 'uploads/recommended/test.jpg',
        'description' => '<p>Recommended content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('recommended', ['name' => 'Test Recommended']);
});

// ─── Random Acts ───────────────────────────────────────────────────────

test('admin can access random acts list page', function () {
    $this->get('/admin/random-acts')->assertSuccessful();
});

test('admin can create a random act via livewire', function () {
    setFormData(Livewire::test(CreateRandomActs::class), [
        'name' => 'Test Random Act',
        'image' => 'uploads/random-acts/test.jpg',
        'description' => '<p>Random act content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('random_acts_of_kindness', ['name' => 'Test Random Act']);
});

// ─── Brand Guidelines ──────────────────────────────────────────────────

test('admin can access brand guidelines list page', function () {
    $this->get('/admin/brand-guidelines')->assertSuccessful();
});

test('admin can create a brand guideline via livewire', function () {
    setFormData(Livewire::test(CreateBrandGuideline::class), [
        'name' => 'Test Brand Guideline',
        'image' => 'uploads/brand-guidelines/test.jpg',
        'description' => '<p>Brand guideline content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('brand_guidelines', ['name' => 'Test Brand Guideline']);
});

// ─── News ──────────────────────────────────────────────────────────────

test('admin can access news list page', function () {
    $this->get('/admin/news')->assertSuccessful();
});

test('admin can create a news item via livewire', function () {
    setFormData(Livewire::test(CreateNews::class), [
        'name' => 'Test News',
        'image' => 'uploads/news/test.jpg',
        'type' => 'news',
        'priority' => 1,
        'description' => '<p>News content</p>',
        'published' => '2026-01-01 00:00:00',
        'meta_title' => 'Test',
        'meta_keywords' => 'test',
        'meta_description' => 'Test',
    ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('news', ['name' => 'Test News', 'type' => 'news']);
});

// ─── Unauthenticated ───────────────────────────────────────────────────

test('unauthenticated user cannot access admin blog list', function () {
    auth()->guard('admin')->logout();

    $this->get('/admin/blogs')->assertRedirect();
});
