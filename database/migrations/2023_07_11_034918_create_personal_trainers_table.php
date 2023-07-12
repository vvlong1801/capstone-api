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
        Schema::create('personal_trainers', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->foreignId('creator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certificate_issuer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('certificate')->constrained()->cascadeOnDelete()->default(false);
            $table->string('ID_number');
            $table->string("address");
            $table->string("facebook")->nullable();
            $table->string("youtube")->nullable();
            $table->string("zalo")->nullable();
            $table->timestamp("verified_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_trainers');
    }
};
