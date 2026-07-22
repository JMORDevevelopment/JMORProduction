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
        Schema::create('packages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('link');
            $table->text('name');
            $table->integer('priority');
            $table->text('heading');
            $table->text('description');
            $table->text('image');
            $table->float('discount');
            $table->text('price');
            $table->text('upfront');
            $table->text('category_name');
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
