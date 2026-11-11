<?php

use App\Support\PrivacyPolicyContentFix;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Testing report issue 041: correct wrong domain, remove unrelated
     * Chamber remarketing copy, update the effective date, fix grammar.
     */
    public function up(): void
    {
        PrivacyPolicyContentFix::apply();
    }

    /**
     * Content correction is intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
