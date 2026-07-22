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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->integer('user_group_id')->nullable();
            $table->string('firstname', 300)->nullable();
            $table->string('lastname', 300)->nullable();
            $table->string('image')->nullable();
            $table->date('date_birth')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('ip', 40)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('zip', 50)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('email', 96)->nullable();
            $table->date('date_added')->nullable();
            $table->string('password', 32)->nullable();
            $table->integer('nation_id')->nullable();
            $table->tinyInteger('ban')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
