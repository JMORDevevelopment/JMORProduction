<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('email', 50);
            $table->string('phone', 50);
            $table->string('reason', 100);
            $table->text('message');
            $table->integer('status')->default(1);
            $table->text('ip')->nullable();
            $table->string('date_time', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
