<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Revision-email templates shown in the talk show guest admin
     * (original: CI Talk_show_guests::templates / submit_templates).
     */
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('name');
            $table->mediumText('content');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
