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
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->char('id_card_number');
            $table->string('name');
            $table->string('password');
            $table->date('born_date');
            $table->enum('gender',['male','female']);
            $table->text('address');
            $table->unsignedBigInteger('regional_id');
            $table->text("login_tokens")->nullable();
            $table->foreign('regional_id')->references('id')->on('regionals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societies');
    }
};
