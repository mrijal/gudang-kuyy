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
        Schema::table('outbound_details', function (Blueprint $table) {
            // drop foreign key
            $table->dropForeign(['outbound_id']);
            $table->dropForeign(['product_id']);

            $table->foreign('outbound_id')->references('id')->on('outbounds')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outbound_details', function (Blueprint $table) {
            // drop foreign key
            $table->dropForeign(['outbound_id']);
            $table->dropForeign(['product_id']);

            $table->foreign('outbound_id')->references('id')->on('outbounds');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
};
