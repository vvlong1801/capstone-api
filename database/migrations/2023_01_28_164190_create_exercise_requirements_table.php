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
        Schema::create('exercise_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_exercise_id');
            $table->foreign('session_exercise_id')->references('id')->on('exercise_workout_session')->cascadeOnDelete();
            $table->string('param');
            $table->string('param_type');
            $table->double('value');
            $table->string('unit');
            $table->integer('order');
            $table->timestamps();

            // constrained between requirements

            // $table->unsignedBigInteger('constrained');
            // $table->foreign('constrained')->references('id')->on('exercise_requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_requirements');
    }
};
