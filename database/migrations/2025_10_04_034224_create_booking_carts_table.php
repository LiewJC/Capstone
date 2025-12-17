<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('booking_carts', function (Blueprint $table) {
        $table->id('cart_id'); 
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('service_id');
        $table->double('total_price', 8, 2);
        $table->timestamps();
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_carts');
    }
};
