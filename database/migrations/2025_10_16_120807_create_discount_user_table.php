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
        Schema::create('discount_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('discount_id');
    $table->timestamps();

    $table->unique(['user_id', 'discount_id']); 

    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
    $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_user');
    }
};
