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
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('stock_barang');
        });
    }

    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('stock_barang')->default(0);
        });
    }
};
