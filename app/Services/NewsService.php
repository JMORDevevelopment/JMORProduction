<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\News;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

class NewsService
{
    private const PER_PAGE = 5;

    public function paginatedList(int $start = 0): array
    {
<<<<<<< HEAD
        $total = News::count();
        $news = News::offset($start)->limit(self::PER_PAGE)->get();
        $to = min($start + self::PER_PAGE, $total);

        return [
            'news' => $news->map(fn ($n) => $n->toArray())->toArray(),
=======
        $total = DB::table('news')->count();
        $news = DB::table('news')->offset($start)->limit(self::PER_PAGE)->get()->toArray();
        $to = min($start + self::PER_PAGE, $total);

        return [
            'news' => array_map(fn ($n) => (array) $n, $news),
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            'text_showing' => $total > 0
                ? sprintf('Showing %d to %d of %d', $start + 1, $to, $total)
                : 'No results',
        ];
    }
}
