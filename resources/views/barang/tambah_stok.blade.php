@extends('layouts.main')

@section('content')
<div class="container">
    <div class="section">
        <div class="section-header">
            <h1>Tambah Stok Barang</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Barang: {{ $barang->nama_barang }}</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('barang.tambahStok', $barang->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="jumlah_stok"><i class="fas fa-box"></i> Jumlah Stok</label>
                        <input type="number" name="jumlah_stok" class="form-control @error('jumlah_stok') is-invalid @enderror"  value="{{ old('jumlah_stok') }}">
                        @error('jumlah_stok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_pembelian"><i class="fas fa-calendar"></i> Tanggal Pembelian</label>
                        <input type="date" name="tanggal_pembelian" class="form-control @error('tanggal_pembelian') is-invalid @enderror"  value="{{ old('tanggal_pembelian') }}">
                        @error('tanggal_pembelian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_kedaluarsa"><i class="fas fa-calendar-times"></i> Tanggal Kedaluwarsa</label>
                        <input type="date" name="tanggal_kedaluarsa" class="form-control @error('tanggal_kedaluarsa') is-invalid @enderror"  value="{{ old('tanggal_kedaluarsa') ?? $barang->tanggal_kedaluarsa ?? '' }}">
                        @error('tanggal_kedaluarsa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Stok</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
