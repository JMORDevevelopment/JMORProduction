<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_radio_show', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('menu_status')->default(0);
            $table->integer('parent_id')->nullable()->default(0);
            $table->text('sub_title');
            $table->text('description');
            $table->text('image')->nullable();
            $table->text('link');
            $table->timestamp('published')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_radio_show');
    }
};
