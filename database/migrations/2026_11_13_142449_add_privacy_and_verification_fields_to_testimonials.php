<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('testimony_form')) {
            return;
        }

        $columns = collect(Schema::getColumns('testimony_form'))->keyBy('name');

        Schema::table('testimony_form', function (Blueprint $table) use ($columns) {
            if (! $columns['customer_id']['nullable']) {
                $table->integer('customer_id')->nullable()->change();
            }

            if (! $columns->has('display_name')) {
                $table->string('display_name', 100)->nullable()->after('customer_id');
            }

            if (! $columns->has('consent_to_publish')) {
                $table->boolean('consent_to_publish')->default(false)->after('message');
            }

            if (! $columns->has('verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('consent_to_publish');
            }

            if (! $columns->has('source_url')) {
                $table->text('source_url')->nullable()->after('verified_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('testimony_form')) {
            return;
        }

        Schema::table('testimony_form', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'consent_to_publish', 'verified_at', 'source_url']);
        });

        Schema::table('testimony_form', function (Blueprint $table) {
            $table->integer('customer_id')->nullable(false)->change();
        });
    }
};
