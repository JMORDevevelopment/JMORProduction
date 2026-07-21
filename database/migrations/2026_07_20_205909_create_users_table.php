<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('user_id');
            $table->integer('user_group_id')->nullable();
            $table->string('firstname', 300)->nullable();
            $table->string('lastname', 300)->nullable();
            $table->string('image', 255)->nullable();
            $table->date('date_birth')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('ip', 40)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('zip', 50)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('email', 96)->unique();
            $table->date('date_added')->nullable();
            $table->string('password', 32)->nullable(); // MD5 hash length
            $table->integer('nation_id')->nullable();
            $table->tinyInteger('ban')->nullable()->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};