@extends('layouts.main')

@section('header')
    Edit Barang
@endsection

@section('content')
    <div class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang"
                                value="{{ old('kode_barang', $barang->kode_barang) }}"
                                class="form-control @error('kode_barang') is-invalid @enderror">
                            @error('kode_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang', $barang->nama_barang) }}"
                                class="form-control @error('nama_barang') is-invalid @enderror">
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pembelian">Tanggal Pembelian</label>
                                    <input type="date" id="tanggal_pembelian" name="tanggal_pembelian"
                                        value="{{ old('tanggal_pembelian', $barang->tanggal_pembelian) }}"
                                        class="form-control datepicker @error('tanggal_pembelian') is-invalid @enderror">
                                    @error('tanggal_pembelian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_kedaluarsa">Tanggal Kedaluwarsa</label>
                                    <input type="date" id="tanggal_kedaluarsa" name="tanggal_kedaluarsa"
                                    value="{{ old('tanggal_kedaluarsa', optional($barang->tanggal_kedaluarsa)->format('Y-m-d')) }}"
                                    class="form-control @error('tanggal_kedaluarsa') is-invalid @enderror">
                                
                                    @error('tanggal_kedaluarsa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                         --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <!-- Hidden input untuk menyimpan angka tanpa format -->
                                    <input type="hidden" id="harga_jual_hidden" name="harga_jual" value="{{ $barang->harga_jual }}">
                                
                                    <!-- Input tampilan dengan format rupiah -->
                                    <input type="text" id="harga_jual" 
                                           value="{{ old('harga_jual', number_format($barang->harga_jual, 0, ',', '.')) }}"
                                           class="form-control" oninput="hitungHpp()">
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_barang">Stok Barang</label>
                                    <input type="number" id="stock_barang" name="stock_barang"
                                        value="{{ old('stock_barang', $barang->stock_barang) }}" class="form-control">
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <label for="minimal_stok">Minimal Stok</label>
                                <input type="number" id="minimal_stok" name="minimal_stok"
                                       value="{{ old('minimal_stok', $barang->minimal_stok ?? '') }}"
                                       class="form-control @error('minimal_stok') is-invalid @enderror">
                                @error('minimal_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select id="kategori_id" name="kategori_id" class="form-control select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ old('kategori_id', $barang->kategori_id) == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>HPP (Harga Pokok Penjualan)</label>
                            <div class="row text-center font-weight-bold mb-2">
                                <div class="col-md-4">
                                    <label for="hpp_tipe1">Tipe 1</label>
                                </div>
                                <div class="col-md-4">
                                    <label for="hpp_tipe2">Tipe 2</label>
                                </div>
                                <div class="col-md-4">
                                    <label for="hpp_tipe3">Tipe 3</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" id="hpp_tipe1" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="hpp_tipe2" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="hpp_tipe3" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 0
            }).format(angka);
        }
    
        function hitungHpp() {
            let hargaJualInput = document.getElementById('harga_jual');
            let hargaJual = parseFloat(hargaJualInput.value.replace(/[^\d]/g, '')) || 0;
    
            let hppTipe1 = hargaJual * 1.10;
            let hppTipe2 = hargaJual * 1.20;
            let hppTipe3 = hargaJual * 1.30;
    
            document.getElementById('hpp_tipe1').value = formatRupiah(hppTipe1);
            document.getElementById('hpp_tipe2').value = formatRupiah(hppTipe2);
            document.getElementById('hpp_tipe3').value = formatRupiah(hppTipe3);
    
            // Simpan nilai tanpa format ke input hidden
            document.getElementById('hpp_tipe1_hidden').value = hppTipe1;
            document.getElementById('hpp_tipe2_hidden').value = hppTipe2;
            document.getElementById('hpp_tipe3_hidden').value = hppTipe3;
        }
    
        document.getElementById('harga_jual').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value ? formatRupiah(parseInt(value)) : '';
            document.getElementById('harga_jual_hidden').value = value || 0;
            hitungHpp();
        });
    
        document.addEventListener('DOMContentLoaded', function() {
            hitungHpp();
        });
    
        // Pastikan angka tanpa format dikirim ke server
        document.querySelector('form').addEventListener('submit', function() {
            let hargaJual = document.getElementById('harga_jual').value.replace(/\D/g, '');
            document.getElementById('harga_jual_hidden').value = hargaJual;
        });
    </script>
    
@endsection
