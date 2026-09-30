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
        Schema::create('talk_show', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('last_name');
            $table->text('company_name');
            $table->text('phone');
            $table->text('email');
            $table->text('bio');
            $table->text('work_name');
            $table->text('work_detail');
            $table->text('weblink');
            $table->text('interview');
            $table->text('ip');
            $table->text('date_time');
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talk_show');
    }
};
