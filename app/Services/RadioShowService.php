<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\CategoryRadioShow;
use App\Models\RadioShow;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

class RadioShowService
{
    /**
     * Build the view data for a radio show category page, including its
     * child categories and every show belonging to the category tree.
     */
    public function categoryViewData(string $categoryLink, ?string $currentYear = null): array
    {
<<<<<<< HEAD
        $category = CategoryRadioShow::where('link', $categoryLink)->first();
=======
        $category = DB::table('category_radio_show')->where('link', $categoryLink)->first();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if (! $category) {
            abort(404);
        }

<<<<<<< HEAD
        $children = CategoryRadioShow::where('parent_id', $category->id)->get()->all();

        return [
            'category_radio_show' => [$category->toArray()],
=======
        $children = DB::table('category_radio_show')->where('parent_id', $category->id)->get()->toArray();

        return [
            'category_radio_show' => [(array) $category],
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            'top_title' => $category->title,
            'category_id' => $category->id,
            'currentYear' => $currentYear,
            'category_radio_show_child' => $children,
            'title' => $category->meta_title ?: $category->title,
            'description' => $category->meta_keywords ?? '',
            'keywords' => $category->meta_description ?? '',
            'show_datas' => $this->showsForCategory($category, $children),
        ];
    }

    /**
     * Shows directly in a category; if it's a top-level (parent_id == 0)
     * category, also includes shows from every child category.
     */
<<<<<<< HEAD
    private function showsForCategory(CategoryRadioShow $category, array $children): array
    {
        if ($category->parent_id != 0) {
            return RadioShow::where('category_id', $category->id)
                ->orderBy('id', 'desc')
                ->get()
                ->all();
        }

        $ownShows = RadioShow::where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->get()
            ->all();
=======
    private function showsForCategory(object $category, array $children): array
    {
        if ($category->parent_id != 0) {
            return DB::table('radio_show')
                ->where('category_id', $category->id)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        }

        $ownShows = DB::table('radio_show')
            ->where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if (empty($children)) {
            return $ownShows;
        }

        $childIds = array_column($children, 'id');
<<<<<<< HEAD
        $childShows = RadioShow::whereIn('category_id', $childIds)
            ->orderBy('id', 'desc')
            ->get()
            ->all();
=======
        $childShows = DB::table('radio_show')
            ->whereIn('category_id', $childIds)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        return array_merge($ownShows, $childShows);
    }
}
