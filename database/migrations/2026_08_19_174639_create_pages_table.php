<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->text('name');
            $table->integer('priority')->default(0);
            $table->integer('slider_status')->default(0);
            $table->integer('menu_location')->default(0);
            $table->text('description');
            $table->text('image')->nullable();
            $table->integer('form_id')->default(0);
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('menu_status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
