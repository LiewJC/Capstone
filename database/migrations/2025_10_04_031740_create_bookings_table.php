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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id('booking_id'); 
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('store_id');
        $table->dateTime('datetime'); 
        $table->string('status');
        $table->timestamps();
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
