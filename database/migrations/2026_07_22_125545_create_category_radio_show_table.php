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
        Schema::create('category_radio_show', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->integer('menu_status')->default(0);
            $table->integer('parent_id')->default(0);
            $table->text('sub_title');
            $table->text('description');
            $table->text('image');
            $table->text('link');
            $table->timestamp('published')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_radio_show');
    }
};
