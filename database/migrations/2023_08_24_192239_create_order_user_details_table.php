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
        Schema::create('order_user_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_productid');
            $table->foreign('order_productid')->references('id')->on('product_orders')->onDelete('cascade');

            $table->unsignedBigInteger('med_id')->nullable();
            $table->foreign('med_id')->references('id')->on('med_pharmacies')->onDelete('cascade');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->boolean('status');


            $table->integer('quantity');
            $table->double('price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_user_details');
    }
};
