<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMembershipPoinAndPoinDidapatToInteger extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('membership_poin')->default(0)->change();
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->integer('poin_didapat')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('membership_poin', 10, 2)->default(0)->change();
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('poin_didapat', 10, 2)->default(0)->change();
        });
    }
}