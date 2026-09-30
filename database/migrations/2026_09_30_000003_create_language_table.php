<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mirrors the original CI `language` table (english / macedonian rows
     * are managed through the admin Languages screen).
     */
    public function up(): void
    {
        Schema::create('language', function (Blueprint $table) {
            $table->increments('language_id');
            $table->string('name', 255);
            $table->string('code', 5);
            $table->string('image', 255)->nullable();
            $table->integer('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language');
    }
};
