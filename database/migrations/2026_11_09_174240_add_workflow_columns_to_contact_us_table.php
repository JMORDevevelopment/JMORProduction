<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add workflow columns for the distinct inquiry, assessment, and
     * support workflows (testing report issue 055).
     */
    public function up(): void
    {
        if (! Schema::hasTable('contact_us')) {
            return;
        }

        $adds = [
            'assessment_scope' => function (Blueprint $table) {
                $table->string('assessment_scope', 20)->nullable()->after('reason');
            },
            'business_name' => function (Blueprint $table) {
                $table->string('business_name', 100)->nullable()->after('assessment_scope');
            },
            'business_type' => function (Blueprint $table) {
                $table->string('business_type', 100)->nullable()->after('business_name');
            },
            'resume_path' => function (Blueprint $table) {
                $table->string('resume_path', 255)->nullable()->after('business_type');
            },
        ];

        foreach ($adds as $column => $definition) {
            if (! Schema::hasColumn('contact_us', $column)) {
                Schema::table('contact_us', $definition);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally a no-op: column removal is unsafe on the shared
        // production database where other branches may depend on them.
    }
};
