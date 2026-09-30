<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    /**
     * Original: Category::index($id = 0) — scaffold category page
     * reached at /category/index/{id}.
     */
    public function index(int $id = 0): View
    {
        $category = Category::query()->where('category_id', $id)->first();

        abort_unless($category, 404);

        return view('frontend.category', [
            'title' => $category->name,
            'description' => __('category.text_description'),
            'keywords' => '',
            'category' => $category,
        ]);
    }
}
