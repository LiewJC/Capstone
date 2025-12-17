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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id('schedule_id');
        $table->unsignedBigInteger('store_id'); 
        $table->string('day_of_week');
        $table->string('start_time'); 
        $table->string('end_time');
        $table->timestamps();
        $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
