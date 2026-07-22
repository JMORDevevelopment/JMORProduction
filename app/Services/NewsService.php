<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NewsService
{
    private const PER_PAGE = 5;

    public function paginatedList(int $start = 0): array
    {
        $total = DB::table('news')->count();
        $news = DB::table('news')->offset($start)->limit(self::PER_PAGE)->get()->toArray();
        $to = min($start + self::PER_PAGE, $total);

        return [
            'news' => array_map(fn ($n) => (array) $n, $news),
            'text_showing' => $total > 0
                ? sprintf('Showing %d to %d of %d', $start + 1, $to, $total)
                : 'No results',
        ];
    }
}
