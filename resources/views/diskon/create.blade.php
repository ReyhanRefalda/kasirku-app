@extends('layouts.main')

@section('title', 'Tambah Diskon')
@section('header')
    Tambah Diskon
@endsection

@section('content')
   
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Form Tambah Diskon</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('diskon.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Diskon</label>
                                <input type="text" class="form-control form-control-sm" name="kode_diskon" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Diskon</label>
                                <input type="text" class="form-control form-control-sm" name="nama_diskon" required>
                            </div>
                            <div class="form-group">
                                <label>Diskon (%)</label>
                                <input type="number" class="form-control form-control-sm" name="diskon_persen" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Minimal Pembelian</label>
                                <input type="text" class="form-control form-control-sm" name="min_pembelanjaan" id="min_pembelanjaan" required onkeyup="formatRupiah(this)">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" class="form-control form-control-sm" name="tanggal_mulai" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Berakhir</label>
                                <input type="date" class="form-control form-control-sm" name="tanggal_berakhir" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ route('diskon.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(angka) {
            let number_string = angka.value.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            angka.value = rupiah;
        }
    </script>
@endsection
