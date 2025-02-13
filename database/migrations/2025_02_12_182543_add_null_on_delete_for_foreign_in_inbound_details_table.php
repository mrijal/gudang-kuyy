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
        Schema::table('inbound_details', function (Blueprint $table) {
            // drop foreign key
            $table->dropForeign(['inbound_id']);
            $table->dropForeign(['product_id']);

            $table->foreign('inbound_id')->references('id')->on('inbounds')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbound_details', function (Blueprint $table) {
            // drop foreign key
            $table->dropForeign(['inbound_id']);
            $table->dropForeign(['product_id']);

            $table->foreign('inbound_id')->references('id')->on('inbounds');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
};
