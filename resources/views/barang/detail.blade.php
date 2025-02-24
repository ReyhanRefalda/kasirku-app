@extends('layouts.main')

@section('header')
    Detail Barang - {{ $barang->nama_barang }}
@endsection

@section('content')
<div class="section-body">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h4 class="text-dark font-weight-bold">
                <i class="fas fa-box-open text-primary"></i> Detail Barang
            </h4>
            <div class="card-header-action">
                <a href="{{ route('barang.index') }}" class="btn btn-light text-dark">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted">Nama Barang</th>
                            <td class="font-weight-bold text-dark">{{ $barang->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kategori</th>
                            <td>
                                <span class="badge badge-info px-3 py-2">{{ $barang->kategori->nama ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Harga Jual</th>
                            <td class="text-success font-weight-bold">
                                Rp{{ number_format($barang->harga_jual, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">HPP</th>
                            <td>
                                <span class="badge badge-primary">Tipe 1: Rp{{ number_format($barang->hpp_tipe1, 2, ',', '.') }}</span><br>
                                <span class="badge badge-warning">Tipe 2: Rp{{ number_format($barang->hpp_tipe2, 2, ',', '.') }}</span><br>
                                <span class="badge badge-danger">Tipe 3: Rp{{ number_format($barang->hpp_tipe3, 2, ',', '.') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>
                                @if ($barang->stok->where('tanggal_kedaluarsa', '>=', now())->count() > 0)
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
             
            </div>

            <h5 class="mt-4"><i class="fas fa-clipboard-list text-primary"></i> Detail Stok</h5>
            <div class="table-responsive">
                <table class="table table-hover rounded-lg">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Jumlah Stok</th>
                            <th>Tanggal Pembelian</th>
                            <th>Tanggal Kedaluwarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang->stok as $stok)
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
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
