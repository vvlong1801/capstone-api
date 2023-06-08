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
        Schema::create('challenge_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->integer('order');
            $table->integer('level');
            $table->string('name')->nullable();
            $table->integer('min_rank')->default(0)->comment('0: beginner, 1: middle, 2:senior, 3:pro');
            $table->integer('max_rank')->default(4)->comment('0: beginner, 1: middle, 2:senior, 3:pro');
            $table->integer('count_sessions');
            $table->integer('active_days');
            $table->integer('rest_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_phases');
    }
};
