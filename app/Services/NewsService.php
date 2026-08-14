<?php

namespace App\Services;

use App\Models\News;

class NewsService
{
    private const PER_PAGE = 5;

    private const NUM_LINKS = 5;

    public function paginatedList(int $start = 0): array
    {
        $total = News::count();
        $news = News::offset($start)->limit(self::PER_PAGE)->get();
        $to = min($start + self::PER_PAGE, $total);

        return [
            'news' => $news->map(fn ($n) => $n->toArray())->toArray(),
            'text_showing' => $total > 0
                ? sprintf('Showing %d to %d from %d items', $start + 1, $to, $total)
                : 'No result',
            'pagination' => $this->links($total, $start),
        ];
    }

    /**
     * Build the pagination links for the news listing, mirroring the
     * CodeIgniter pagination markup used by the CI project.
     */
    private function links(int $total, int $start): string
    {
        $pageCount = (int) ceil($total / self::PER_PAGE);

        if ($pageCount <= 1) {
            return '';
        }

        $current = (int) floor($start / self::PER_PAGE);

        $html = '';

        if ($current > 0) {
            $html .= '<li><a href="'.$this->urlFor(0).'">&lt;&lt;</a></li>';
            $html .= '<li class="previous"><a href="'.$this->urlFor($current - 1).'">&lt;</a></li>';
        }

        $from = max(0, $current - self::NUM_LINKS);
        $to = min($pageCount - 1, $current + self::NUM_LINKS);

        for ($page = $from; $page <= $to; $page++) {
            if ($page === $current) {
                $html .= '<li class="active"><a href="#">'.($page + 1).'</a></li>';
            } else {
                $html .= '<li><a href="'.$this->urlFor($page).'">'.($page + 1).'</a></li>';
            }
        }

        if ($current < $pageCount - 1) {
            $html .= '<li class="paginate_button next"><a href="'.$this->urlFor($current + 1).'">&gt;</a></li>';
            $html .= '<li><a href="'.$this->urlFor($pageCount - 1).'">&gt;&gt;</a></li>';
        }

        return $html;
    }

    private function urlFor(int $page): string
    {
        return route('news.index', ['start' => $page * self::PER_PAGE]);
    }
}
