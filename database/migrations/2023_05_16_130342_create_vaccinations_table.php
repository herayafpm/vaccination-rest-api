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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->integer('queue')->default(1);
            $table->tinyInteger('dose')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('spot_id');
            $table->unsignedBigInteger('society_id');
            $table->unsignedBigInteger('vaccine_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('officer_id')->nullable();
            $table->enum('status',['pending','done'])->default('pending');
            $table->foreign('spot_id')->references('id')->on('spots');
            $table->foreign('society_id')->references('id')->on('societies');
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('doctor_id')->references('id')->on('medicals');
            $table->foreign('officer_id')->references('id')->on('medicals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
