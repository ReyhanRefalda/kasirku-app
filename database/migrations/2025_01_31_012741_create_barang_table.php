<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->date('tanggal_kedaluarsa');
            $table->date('tanggal_pembelian');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->decimal('harga_jual', 15, 2); // Menyimpan harga jual barang
            $table->integer('stock_barang'); // Menyimpan stok barang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
