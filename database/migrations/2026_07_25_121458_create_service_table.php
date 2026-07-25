<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->increments('service_id');
            $table->string('title', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->string('meta_title', 100)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('keywords', 100)->nullable();
            $table->text('link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};