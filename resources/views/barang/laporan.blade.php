@extends('layouts.main')

@section('header')
    Laporan Stok Barang
@endsection

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action text-left">
                    <a href="{{ route('barang.export') }}" class="btn btn-success">Export ke Excel</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok</th>
                                <th>Tanggal Pembelian</th>
                                <th>Tanggal Kedaluarsa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $index => $item)
                                <tr class="bg-light">
                                    <td rowspan="{{ count($item->stok) + 1 }}">{{ $index + 1 }}</td>
                                    <td rowspan="{{ count($item->stok) + 1 }}">{{ $item->nama_barang }}</td>
                                </tr>
                                @foreach ($item->stok as $stok)
                                    <tr class="border-bottom">
                                        <td><span class="badge badge-secondary px-3 py-2">{{ $stok->jumlah_stok }}</span></td>
                                        <td class="text-dark">{{ \Carbon\Carbon::parse($stok->tanggal_pembelian)->format('d M Y') }}</td>
                                        <td class="font-weight-bold 
                                            @if ($stok->tanggal_kedaluarsa < now()) text-danger 
                                            @else text-success 
                                            @endif">
                                            {{ \Carbon\Carbon::parse($stok->tanggal_kedaluarsa)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
