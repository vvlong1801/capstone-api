<?php

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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('type')->default(1)->comment('1: fixed');
            $table->integer('max_member')->default(-1)->comment('-1: non-limited');
            $table->integer('commit_point')->nullable();
            $table->integer('participant')->default(1)->comment('1: all, 2: group, 3: assign');
            $table->boolean('required_approve')->default(false);
            $table->integer('member_censorship')->default(1);
            $table->integer('result_censorship')->default(1);
            $table->integer('status')->default(0)->comment('0: init, 1: waiting, 2: active, 3: finish, 4:pending');
            $table->dateTime('released_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('stopped_at')->nullable();
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
        Schema::dropIfExists('challenges');
    }
};
