<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $penjualan;

    public function __construct($penjualan)
    {
        $this->penjualan = $penjualan;
    }

    public function collection(): Collection
    {
        return collect($this->penjualan);
    }

    public function headings(): array
{
    return [
        'No', 'Nama Pembeli', 'Nama Kasir', 'Tanggal Transaksi',
        'Total Belanja', 'Diskon (%)', 'PPN (12%)', 'Total Akhir',
        'Uang Masuk', 'Kembalian', 'Barang Dibeli', 'Jumlah Barang'
    ];
}

public function map($penjualan): array
{
    $rows = [];

    foreach ($penjualan->detailPenjualan as $index => $detail) {
        $rows[] = [
            $index === 0 ? $penjualan->id : '', // ID hanya muncul di baris pertama
            $index === 0 ? optional($penjualan->pengguna)->name ?? '-' : '', // Nama Pembeli hanya muncul di baris pertama
            $index === 0 ? optional($penjualan->kasir)->name ?? '-' : '', // Nama Kasir hanya muncul di baris pertama
            $index === 0 ? optional($penjualan->created_at)->format('d M Y H:i:s') ?? '-' : '', // Tanggal Transaksi hanya di baris pertama
            $index === 0 ? number_format($penjualan->total_pembelanjaan, 2) : '', // Total Belanja hanya di baris pertama
            $index === 0 ? $penjualan->diskon_persen ?? 0 : '', // Diskon hanya di baris pertama
            $index === 0 ? number_format(($penjualan->total_pembelanjaan - ($penjualan->total_pembelanjaan * ($penjualan->diskon_persen ?? 0)) / 100) * 0.12, 2) : '', // PPN hanya di baris pertama
            $index === 0 ? number_format($penjualan->total_akhir, 2) : '', // Total Akhir hanya di baris pertama
            $index === 0 ? number_format($penjualan->uang_masuk, 2) : '', // Uang Masuk hanya di baris pertama
            $index === 0 ? number_format($penjualan->uang_kembalian, 2) : '', // Kembalian hanya di baris pertama
            $detail->nama_barang, // Nama Barang ditampilkan di tiap baris
            $detail->quantity, // Jumlah Barang ditampilkan di tiap baris
        ];
    }

    return $rows;
}


}
