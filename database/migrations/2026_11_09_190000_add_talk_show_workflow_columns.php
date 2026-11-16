<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('talk_show') && ! Schema::hasColumn('talk_show', 'signature')) {
            Schema::table('talk_show', function (Blueprint $table) {
                $table->string('signature', 100)->nullable()->after('interview');
                $table->string('signed_pdf_path', 255)->nullable()->after('signature');
            });
        }

        if (Schema::hasTable('talk_show_settings') && ! Schema::hasColumn('talk_show_settings', 'agreement_path')) {
            Schema::table('talk_show_settings', function (Blueprint $table) {
                $table->string('agreement_path', 255)->nullable()->after('question');
            });
        }

        if (! Schema::hasTable('talk_show_audits')) {
            Schema::create('talk_show_audits', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('talk_show_id');
                $table->string('action', 50);
                $table->unsignedTinyInteger('old_status')->nullable();
                $table->unsignedTinyInteger('new_status')->nullable();
                $table->string('ip', 45)->nullable();
                $table->string('user_agent', 255)->nullable();
                $table->text('details')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->index('talk_show_id');
            });
        }
    }

    public function down(): void
    {
        // Intentionally a no-op: additive schema only.
    }
};
