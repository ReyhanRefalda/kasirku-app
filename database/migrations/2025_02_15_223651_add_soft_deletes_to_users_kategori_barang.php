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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // Tambahkan kolom deleted_at
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->softDeletes(); // Tambahkan kolom deleted_at
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->softDeletes(); // Tambahkan kolom deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
