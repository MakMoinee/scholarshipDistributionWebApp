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
        Schema::create('cashins', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('userID');
            $table->decimal('amount', 10, 2);
            $table->string('ethAmount');
            $table->string('transactionHash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashins');
    }
};
