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
        Schema::create('descrptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordetal_id');
            $table->foreign('ordetal_id')->references('id')->on('order_user_details')->onDelete('cascade');

            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descrptions');
    }
};
