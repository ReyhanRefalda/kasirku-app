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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('users')->onDelete('cascade'); // Pembeli
            $table->foreignId('id_kasir')->constrained('users')->onDelete('cascade'); // Kasir
            $table->foreignId('id_diskon')->nullable()->constrained('diskon')->onDelete('set null'); // Diskon yang digunakan
            $table->integer('total_pembelanjaan'); // Total sebelum diskon
            $table->integer('diskon_persen')->default(0); // Diskon dalam persen
            $table->integer('nominal_diskon')->default(0); // Nominal diskon (dihitung dari persen)
            $table->integer('used_poin')->default(0); // Poin yang digunakan
            $table->integer('total_akhir'); // Total setelah diskon
            $table->integer('uang_masuk'); // Uang yang diberikan pelanggan
            $table->integer('uang_kembalian'); // Kembalian
            $table->timestamps();
        });
        
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penjualan')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade');
            $table->integer('quantity'); // Jumlah barang yang dibeli
            $table->integer('subtotal_harga'); // Harga * quantity
            $table->timestamps(); // Created_at (kapan barang ini dibeli)
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
        Schema::dropIfExists('penjualan');
    }
};
