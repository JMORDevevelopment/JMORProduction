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
        Schema::create('radio_show', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('link');
            $table->text('name');
            $table->text('description');
            $table->date('show_date')->nullable();
            $table->integer('category_id');
            $table->text('image');
            $table->timestamp('published')->useCurrent()->useCurrentOnUpdate();
            $table->text('meta_title');
            $table->text('meta_keywords');
            $table->text('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_show');
    }
};
