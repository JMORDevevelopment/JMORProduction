<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_information', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('fax')->nullable();
            $table->text('address')->nullable();
            $table->text('suite')->nullable();
            $table->text('city')->nullable();
            $table->string('state', 80)->nullable();
            $table->string('zip', 80)->nullable();
            $table->string('service_intersted', 100)->nullable();
            $table->text('message')->nullable();
            $table->integer('protection_question')->nullable();
            $table->integer('status')->nullable();
            $table->text('ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_information');
    }
};
