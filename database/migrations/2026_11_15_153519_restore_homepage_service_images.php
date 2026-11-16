<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Restores the homepage service icons (testing report 037).
 *
 * The port kept the CI image paths (`uploads/...`), but that directory is not
 * deployed, so the "Our Services" cards rendered with the generic fallback.
 * Point each row at the matching tracked asset instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('service')) {
            return;
        }

        foreach (DB::table('service')->get() as $service) {
            $image = trim((string) ($service->image ?? ''));

            if ($image === '') {
                continue;
            }

            $basename = basename(str_replace('\\', '/', $image));
            $tracked = 'assets/images/'.$basename;

            if ($image === $tracked || ! is_file(public_path($tracked))) {
                continue;
            }

            DB::table('service')
                ->where('service_id', $service->service_id)
                ->update(['image' => $tracked]);
        }
    }

    public function down(): void
    {
        // The previous uploads/ paths were never deployed, so there is nothing
        // useful to restore.
    }
};
