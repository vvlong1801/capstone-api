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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('mediable_type')->nullable()->comment('1: user, 2: muscle, 3: equipment, 4: challenge, 5: plan, 6: music');
            $table->integer('mediable_id')->nullable();
            $table->string('collection_name')->comment('0: test, 1: avatars, 2: muscles, 3: equipments, 4: challenges, 5: exercises, 6: plans, 7: musics');
            $table->string('name');
            $table->string('path');
            $table->unique(['name', 'path']);
            $table->integer('type')->comment('1: image, 2: icon, 3: gif, 4: video, 5: music');
            $table->string('disk')->comment('0: local, 1: s3, 2: local-tmp, 3: s3-tmp');
            $table->string('conversions_disk')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('media');
    }
};
