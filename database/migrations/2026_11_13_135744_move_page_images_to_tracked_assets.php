<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Page images in uploads/pages are not committed, so a fresh deployment
     * served broken images. The approved files now live in public assets.
     */
    private const IMAGE_MAP = [
        'uploads/pages/1441422.jpg' => 'assets/images/pages/case-study.jpg',
        'uploads/pages/1065250716technical-relocation-service.jpg' => 'assets/images/pages/technical-relocation-service.jpg',
        'uploads/pages/1715462809nj-custom-built-solutions.jpg' => 'assets/images/pages/custom-built-solutions.jpg',
        'uploads/pages/1401821031Man in Warehouse with Mask.jpg' => 'assets/images/pages/man-in-warehouse-with-mask.jpg',
        'uploads/pages/819382798networksecurity.jpg' => 'assets/images/pages/network-security.jpg',
        'uploads/pages/1860828277Firewall.jpg' => 'assets/images/pages/hardware-firewalls.jpg',
        'uploads/pages/01M0R8QG5A67A22MQ1VKWXAB9F.jpg' => 'assets/images/pages/apple-page.jpg',
        'uploads/pages/01M1H3G6W6TY70YEFVMA78VXSP.jpg' => 'assets/images/pages/title-page.jpg',
    ];

    public function up(): void
    {
        foreach (self::IMAGE_MAP as $legacyPath => $trackedPath) {
            DB::table('pages')
                ->where('image', $legacyPath)
                ->update(['image' => $trackedPath]);
        }
    }

    public function down(): void
    {
        foreach (array_flip(self::IMAGE_MAP) as $trackedPath => $legacyPath) {
            DB::table('pages')
                ->where('image', $trackedPath)
                ->update(['image' => $legacyPath]);
        }
    }
};
