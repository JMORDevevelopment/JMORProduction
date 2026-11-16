<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Reads the latest media for the JMOR Instagram account (testing report 034).
 *
 * The Instagram Basic Display API was retired in December 2024, so this uses
 * the current Instagram Platform media edge. Both authentication flavours are
 * supported by changing INSTAGRAM_GRAPH_URL:
 *
 *  - Instagram API with Facebook Login: https://graph.facebook.com/v27.0
 *    (instagram_basic + pages_read_engagement)
 *  - Instagram API with Instagram Login: https://graph.instagram.com/v27.0
 *    (instagram_business_basic)
 *
 * The account must be an Instagram professional (Business or Creator) account.
 */
class InstagramFeed
{
    /**
     * Return the newest published media, or an empty array when the feed is not
     * configured or the request fails. Callers render a profile fallback then.
     *
     * @return array<int, array{id: string, image: string, permalink: string, alt: string, media_type: string}>
     */
    public function latest(int $limit = 9): array
    {
        $userId = trim((string) config('services.instagram.user_id'));
        $accessToken = trim((string) config('services.instagram.access_token'));

        if ($userId === '' || $accessToken === '') {
            return [];
        }

        $limit = max(1, min($limit, 24));
        $cacheKey = 'instagram:feed:'.$userId.':'.$limit;
        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $posts = $this->fetch($userId, $accessToken, $limit);

        // Retry sooner after a failed/empty response than after a healthy one.
        $ttl = $posts === []
            ? min(300, (int) config('services.instagram.cache_ttl', 1800))
            : (int) config('services.instagram.cache_ttl', 1800);

        Cache::put($cacheKey, $posts, $ttl);

        return $posts;
    }

    /**
     * @return array<int, array{id: string, image: string, permalink: string, alt: string, media_type: string}>
     */
    private function fetch(string $userId, string $accessToken, int $limit): array
    {
        $baseUrl = rtrim((string) config('services.instagram.graph_url', 'https://graph.facebook.com/v27.0'), '/');

        $fields = 'id,caption,media_type,media_url,thumbnail_url,permalink,alt_text';

        try {
            $response = $this->request($baseUrl, $userId, $accessToken, $limit, $fields);

            // alt_text is only defined for image posts. If the account has
            // media types that reject it, retry without it rather than losing
            // the whole feed.
            if ($response->status() === 400) {
                $response = $this->request(
                    $baseUrl,
                    $userId,
                    $accessToken,
                    $limit,
                    'id,caption,media_type,media_url,thumbnail_url,permalink',
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram feed request failed', ['error' => $e->getMessage()]);

            return [];
        }

        if (! $response->successful()) {
            Log::warning('Instagram feed request was rejected', [
                'status' => $response->status(),
                'error' => $response->json('error.message'),
            ]);

            return [];
        }

        $posts = [];

        foreach ((array) $response->json('data', []) as $item) {
            if (! is_array($item)) {
                continue;
            }

            // Videos/reels expose a thumbnail instead of media_url.
            $image = $item['thumbnail_url'] ?? $item['media_url'] ?? null;
            $permalink = $item['permalink'] ?? null;

            if (! is_string($image) || $image === '' || ! is_string($permalink) || $permalink === '') {
                continue;
            }

            $caption = trim(strip_tags((string) ($item['caption'] ?? '')));
            $altText = trim((string) ($item['alt_text'] ?? ''));

            $posts[] = [
                'id' => (string) ($item['id'] ?? ''),
                'image' => $image,
                'permalink' => $permalink,
                'alt' => $altText !== '' ? $altText : ($caption !== '' ? Str::limit($caption, 120) : 'JMOR on Instagram'),
                'media_type' => (string) ($item['media_type'] ?? 'IMAGE'),
            ];
        }

        return array_slice($posts, 0, $limit);
    }

    private function request(string $baseUrl, string $userId, string $accessToken, int $limit, string $fields): Response
    {
        return Http::timeout(8)
            ->acceptJson()
            ->get($baseUrl.'/'.$userId.'/media', [
                'fields' => $fields,
                'limit' => $limit,
                'access_token' => $accessToken,
            ]);
    }
}
