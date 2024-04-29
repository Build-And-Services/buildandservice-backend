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
        Schema::create('accept_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('dp');
            $table->string('link_sheet');
            $table->string('link_github')->nullable();
            $table->string('link_drive')->nullable();
            $table->dateTime('deadline');
            $table->integer('fix_price');
            $table->enum('status', ['ON GOING', 'DONE'])->default('ON GOING');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accept_orders');
    }
};
