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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('poin_didapat', 10, 2)->default(0)->after('uang_kembalian');
        });
    }
    
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn('poin_didapat');
        });
    }
    
};
