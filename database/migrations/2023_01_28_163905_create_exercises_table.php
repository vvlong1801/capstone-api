<?php

use App\Enums\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level');
            $table->integer('type')->default(1);
            $table->string('requirement_unit');
            $table->string('requirement_initial');
            $table->unsignedBigInteger('created_by');
            $table->string('youtube_url')->nullable();
            $table->integer('for_gender')->default(3);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->integer('evaluate_method')->comment('0: repitition, 1: time-based, 2: distance-based');
            $table->text('description')->nullable();
            $table->foreignId('equipment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
};
