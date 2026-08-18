<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_inquiries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media', 100)->nullable();
            $table->string('contact', 60)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('story_concept', 100)->nullable();
            $table->date('press_deadline')->nullable();
            $table->text('story_details')->nullable();
            $table->text('best_contact')->nullable();
            $table->text('protection_question')->nullable();
            $table->string('media_status', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_inquiries');
    }
};
