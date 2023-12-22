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
        Schema::create('walletphs', function (Blueprint $table) {
            $table->id();
            $table->double('funds');
            $table->unsignedBigInteger('ph_id');
            $table->foreign('ph_id')->references('id')->on('pharmacies')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walletphs');
    }
};
