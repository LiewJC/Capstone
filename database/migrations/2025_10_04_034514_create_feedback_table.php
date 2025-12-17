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
    Schema::create('feedbacks', function (Blueprint $table) {
        $table->id('feedback_id'); 
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('booking_id');
        $table->string('rating');
        $table->text('comment')->nullable();
        $table->timestamps();
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
