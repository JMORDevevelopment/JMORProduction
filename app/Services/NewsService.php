<?php

namespace App\Services;

use App\Models\News;

class NewsService
{
    private const PER_PAGE = 5;

    public function paginatedList(int $start = 0): array
    {
        $total = News::query()->count();
        $news = News::query()->offset($start)->limit(self::PER_PAGE)->get();
        $to = min($start + self::PER_PAGE, $total);

        return [
            'news' => $news->map(fn ($n) => $n->toArray())->toArray(),
            'text_showing' => $total > 0
                ? sprintf('Showing %d to %d of %d', $start + 1, $to, $total)
                : 'No results',
        ];
    }
}
