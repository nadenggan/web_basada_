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
        Schema::create('cicilan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_pembayaran");
            $table->integer("nominal");
            $table->date("tanggal_bayar");
            $table->timestamps();

            $table->foreign("id_pembayaran")->references("id")->on("pembayaran")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicilan');
    }
};
