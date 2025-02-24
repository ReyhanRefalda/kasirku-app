<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BarangExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $barang = Barang::with('stok')->get();
    
        $data = [];
        $no = 1;
        foreach ($barang as $item) {
            foreach ($item->stok as $stok) {
                $data[] = [
                    'No'                => $no++,
                    'Nama Barang'       => $item->nama_barang,
                    'Jumlah Stok'       => strval($stok->jumlah_stok),
                    'Tanggal Pembelian' => Carbon::parse($stok->tanggal_pembelian)->format('d M Y'),
                    'Tanggal Kedaluarsa'=> Carbon::parse($stok->tanggal_kedaluarsa)->format('d M Y'),
                ];
            }
        }
    
        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Nama Barang', 'Jumlah Stok', 'Tanggal Pembelian', 'Tanggal Kedaluarsa'];
    }
}
