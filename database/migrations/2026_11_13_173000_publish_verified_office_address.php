<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Publishes the verified office address (testing report 045).
 *
 * The old value read "799 Franklin Ave  Unit 3" with a double space, no comma
 * before the city and no state abbreviation, and the map embed pointed at
 * "#3". The verified address is 799 Franklin Avenue, Suite 3, Franklin Lakes,
 * NJ 07417. An address an editor has already corrected is left alone.
 *
 * The published phone number is intentionally not touched here; batch 1
 * already owns that correction.
 */
return new class extends Migration
{
    private const ADDRESS = '799 Franklin Avenue, Suite 3<br>Franklin Lakes, NJ 07417';

    public function up(): void
    {
        DB::table('settings')
            ->where('option', 'address')
            ->where('value', 'like', '%Unit 3%')
            ->update(['value' => self::ADDRESS]);
    }

    public function down(): void
    {
        // The verified address is not reverted.
    }
};
