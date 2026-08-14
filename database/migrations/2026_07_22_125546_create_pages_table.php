<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('link');
            $table->text('name');
            $table->integer('priority');
            $table->integer('slider_status')->default(0);
            $table->integer('menu_location');
            $table->text('description');
            $table->text('image');
            $table->integer('form_id')->default(0);
            $table->text('meta_title');
            $table->text('meta_keywords');
            $table->text('meta_description');
            $table->integer('menu_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
