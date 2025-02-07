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
        Schema::create('outbound_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outbound_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('price_per_unit')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('outbound_id')->references('id')->on('outbounds');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_details');
    }
};
