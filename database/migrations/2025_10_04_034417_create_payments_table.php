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
    Schema::create('payments', function (Blueprint $table) {
        $table->id('payment_id'); 
        $table->unsignedBigInteger('booking_id');
        $table->double('amount', 10, 2);
        $table->string('payment_method');
        $table->string('payment_status');
        $table->dateTime('payment_date');
        $table->timestamps();
        $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
