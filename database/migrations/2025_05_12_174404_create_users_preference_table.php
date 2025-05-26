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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->time('sleep_time')->nullable();
            $table->time('wake_time')->nullable();
            $table->time('breakfast_time')->nullable();
            $table->time('lunch_time')->nullable();
            $table->time('dinner_time')->nullable();
            $table->time('study_time_start')->nullable();
            $table->time('study_time_end')->nullable();
            $table->boolean('is_subscribed')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
