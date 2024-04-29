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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('phone_number');
            $table->string('project_title');
            $table->string('project_description');
            $table->string('techstack_detail');
            $table->string('file_requirement');
            $table->enum('client_type', ['MAHASISWA', 'INSTANSI', 'PRIBADI']);
            $table->enum('project_type', ['WEB', 'MOBILE', 'MACHINE LEARNING', 'CONSULT', 'DESIGN']);
            $table->enum('status', ['ACCEPT', 'REQUEST', 'CANCEL'])->default('REQUEST');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
