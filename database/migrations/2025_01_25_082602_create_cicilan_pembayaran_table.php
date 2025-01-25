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
        Schema::create('cicilan_pembayaran', function (Blueprint $table) {
            $table->id(); // Pada laravel id otomatis bertipe unsignedBigInteger dan menjadi PK
            $table->unsignedBigInteger('id_pembayaran');
            $table->decimal('nominal', 15, 2); // 15 digit total, 2 digit desimal
            $table->date('tanggal_bayar');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicilan_pembayaran');
    }
};
