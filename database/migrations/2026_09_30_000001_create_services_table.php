<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('question');
            $table->text('product_code');
            $table->text('description');
            $table->text('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
