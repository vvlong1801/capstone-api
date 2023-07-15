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
            $table->text('sort_desc')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('type')->default(1)->comment('1: fixed');
            $table->integer('max_members')->default(-1)->comment('-1: non-limited');
            $table->integer('level')->nullable();
            $table->integer('for_gender')->default(3);
            $table->string('youtube_url')->nullable();
            $table->boolean('accept_all')->default(true);
            $table->boolean('public')->default(true);
            $table->integer("rate")->default(0);
            $table->integer("num_rate")->default(0);
            $table->integer('status')->default(0)->comment('0: init, 1: waiting, 2: running, 3: finish, 4:paused, 5:cancel');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('finish_at')->nullable();
            $table->dateTime('paused_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
