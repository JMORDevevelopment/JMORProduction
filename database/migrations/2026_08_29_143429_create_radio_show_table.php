<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('radio_show', function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->text('name');
            $table->text('description');
            $table->date('show_date')->nullable();
            $table->integer('category_id');
            $table->text('image')->nullable();
            $table->timestamp('published')->useCurrent();
            $table->text('meta_title');
            $table->text('meta_keywords');
            $table->text('meta_description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_show');
    }
};
