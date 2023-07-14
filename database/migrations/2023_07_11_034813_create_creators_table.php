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
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('status')->default(1);
            $table->foreignId('certificate_issuer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string("address")->nullable();
            $table->integer('gender')->nullable();
            $table->integer('desired_salary')->nullable();
            $table->integer('work_type')->nullable();
            $table->integer('age')->nullable();
            $table->integer("rate")->default(0);
            $table->integer("num_rate")->default(0);
            $table->string("facebook")->nullable();
            $table->string("youtube")->nullable();
            $table->string("zalo")->nullable();
            $table->text('introduce')->nullable();
            $table->timestamp("verified_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creators');
    }
};
