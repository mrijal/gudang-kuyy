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
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->dateTime('outbound_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('note')->nullable();
            $table->text('shipping_address')->nullable();
            $table->unsignedBigInteger('shipping_fee')->nullable();
            $table->string('shipping_method')->nullable();
            $table->unsignedBigInteger('discount')->nullable();
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('total_payment')->nullable();
            $table->string('payment_history')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbounds');
    }
};
