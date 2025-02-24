@extends('layouts.main')

@section('title', 'Edit Diskon')

@section('header')
    Edit Diskon
@endsection

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Form Edit Diskon</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('diskon.update', $diskon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_diskon">Kode Diskon</label>
                                <input type="text" id="kode_diskon" name="kode_diskon" 
                                    value="{{ old('kode_diskon', $diskon->kode_diskon) }}" 
                                    class="form-control @error('kode_diskon') is-invalid @enderror" required>
                                @error('kode_diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nama_diskon">Nama Diskon</label>
                                <input type="text" id="nama_diskon" name="nama_diskon" 
                                    value="{{ old('nama_diskon', $diskon->nama_diskon) }}" 
                                    class="form-control @error('nama_diskon') is-invalid @enderror" required>
                                @error('nama_diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="diskon_persen">Diskon (%)</label>
                                <input type="number" id="diskon_persen" name="diskon_persen" 
                                    value="{{ old('diskon_persen', $diskon->diskon_persen) }}" 
                                    class="form-control @error('diskon_persen') is-invalid @enderror" required>
                                @error('diskon_persen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_pembelanjaan">Minimal Pembelian</label>
                                <input type="text" id="min_pembelanjaan" name="min_pembelanjaan" 
                                    value="{{ old('min_pembelanjaan', $diskon->min_pembelanjaan) }}" 
                                    class="form-control @error('min_pembelanjaan') is-invalid @enderror" 
                                    onkeyup="formatRupiah(this)" required>
                                @error('min_pembelanjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_mulai">Tanggal Mulai</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                    value="{{ old('tanggal_mulai', $diskon->tanggal_mulai) }}" 
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_berakhir">Tanggal Berakhir</label>
                                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" 
                                    value="{{ old('tanggal_berakhir', $diskon->tanggal_berakhir) }}" 
                                    class="form-control @error('tanggal_berakhir') is-invalid @enderror" required>
                                @error('tanggal_berakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ route('diskon.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            angka.value = rupiah ? 'Rp ' + rupiah : '';
        }
    </script>
@endsection
