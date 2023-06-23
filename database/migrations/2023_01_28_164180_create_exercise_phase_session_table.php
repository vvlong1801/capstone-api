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
        Schema::create('exercise_phase_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('phase_session_id')->constrained()->cascadeOnDelete();
            $table->integer('order');
            $table->string('requirement');
            $table->string('requirement_unit')->nullable();
            $table->boolean('alternative_exercise')->default(false)->comment('true: alter, false: main');
            $table->unsignedBigInteger('main_exercise')->nullable();
            $table->foreign('main_exercise')->references('id')->on('exercise_phase_session')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_phase_session');
    }
};
