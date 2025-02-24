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
            $table->decimal('hpp_tipe1', 15, 2)->nullable();
            $table->decimal('hpp_tipe2', 15, 2)->nullable();
            $table->decimal('hpp_tipe3', 15, 2)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['hpp_tipe1', 'hpp_tipe2', 'hpp_tipe3']);
        });
    }
    
};
