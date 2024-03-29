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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni', length:45)->primary();
            $table->foreignId('id_reg')->references('id_reg')->on('regions');
            $table->foreignId('id_com')->references('id_com')->on('communes');
            $table->string('email', length:120)->unique();
            $table->string('name', length:45);
            $table->string('last_name', length:45);
            $table->string('address', length:255);
            $table->timestamp('date_reg');
            $table->enum('status', ['A', 'I', 'Trash'])->default('A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
