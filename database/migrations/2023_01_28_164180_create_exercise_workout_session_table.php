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
        Schema::create('exercise_workout_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $table->integer('order');
            $table->boolean('is_primary')->default(true)->comment('true: primary, false: alternate');
            $table->unsignedBigInteger('alternative_exercise')->nullable();
            $table->foreign('alternative_exercise')->references('id')->on('exercise_workout_session')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_workout_session');
    }
};
