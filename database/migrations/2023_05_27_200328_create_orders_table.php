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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ph');
            $table->foreign('id_ph')->references('id')->on('pharmacies')->onDelete('cascade');

            $table->boolean('status')->default('0');

            $table->boolean('status_user')->default('0');


            $table->unsignedBigInteger('id_warehouse');
            $table->foreign('id_warehouse')->references('id')->on('warehouses')->onDelete('cascade');

            $table->integer('total_price')->default('0');

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
