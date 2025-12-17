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
    Schema::create('stores', function (Blueprint $table) {
        $table->id('store_id');
        $table->string('name');
        $table->string('address');
        $table->string('contact_number');
        $table->string('latitude');
        $table->string('longitude'); 
        $table->string('operation_hours');
        $table->string('status');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
