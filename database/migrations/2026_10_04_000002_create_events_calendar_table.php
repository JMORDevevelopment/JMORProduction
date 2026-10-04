<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Recording-session dates shown across the talk show admin
     * (original CI: admin/Recording_sessions + the calendars — `events_calendar`).
     */
    public function up(): void
    {
        Schema::create('events_calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->text('date_time');
            $table->text('name');
            $table->text('description');
            $table->integer('user_id')->default(0);
            $table->text('link');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events_calendar');
    }
};
