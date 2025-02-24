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
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->string('nama_barang')->after('id_barang'); // Tambahkan kolom nama_barang
        });
    }
    
    public function down()
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropColumn('nama_barang');
        });
    }
    
};
