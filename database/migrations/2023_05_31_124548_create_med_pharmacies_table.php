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
        Schema::create('med_pharmacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ph_id');
            $table->foreign('ph_id')->references('id')->on('pharmacies')->onDelete('cascade');

            $table->unsignedBigInteger('med_id');
            $table->foreign('med_id')->references('id')->on('medicines')->onDelete('cascade');

            $table->integer('quantity');
            

            $table->string('image');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_pharmacies');
    }
};
