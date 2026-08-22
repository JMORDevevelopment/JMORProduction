<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->integer('admin_id')->primary()->autoIncrement();
            $table->string('firstname', 30);
            $table->string('lastname', 50);
            $table->string('image', 100);
            $table->dateTime('last_login');
            $table->dateTime('date_register');
            $table->string('password', 255);
            $table->string('email', 30);
            $table->integer('status')->default(0);
            $table->integer('role')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
