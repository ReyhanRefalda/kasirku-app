@extends('layouts.main')

@section('title', 'Detail Laporan')
@section('header')
    Detail Nota Penjualan
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="text-center">Detail Penjualan #{{ $penjualan->id }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p>Nama Kasir: {{ $penjualan->nama_kasir }}</p>
                    <p>Nama Pembeli: {{ $penjualan->nama_pembeli }}</p>
                  
                    <p><strong>Tanggal Transaksi:</strong> {{ $penjualan->created_at->format('d M Y H:i:s') }}</p>
                </div>

                <div class="col-md-6">
                    <p><strong>Total Belanja:</strong> Rp {{ number_format($penjualan->total_pembelanjaan, 2) }}</p>

                    <p><strong>Diskon:</strong> {{ $penjualan->diskon_persen ?? 0 }}%
                        (Rp
                        {{ number_format(($penjualan->total_pembelanjaan * ($penjualan->diskon_persen ?? 0)) / 100, 2) }})
                    </p>

                    <p><strong>Poin Digunakan:</strong> {{ number_format($penjualan->used_poin ?? 0) }} poin</p>

                    <p><strong>Poin Didapat:</strong> {{ number_format($penjualan->poin_didapat, 0) }} </p>

                    <p><strong>Sisa Poin Pengguna:</strong> {{ number_format($penjualan->pengguna->membership_poin ?? 0) }}
                        poin</p>

                    <p><strong>PPN (12%):</strong> Rp
                        {{ number_format(($penjualan->total_pembelanjaan - ($penjualan->total_pembelanjaan * ($penjualan->diskon->percentage ?? 0)) / 100) * 0.12, 2) }}
                    </p>

                    <p><strong>Total Setelah Diskon, Poin & PPN:</strong> Rp
                        {{ number_format($penjualan->total_akhir, 2) }}</p>

                    <p><strong>Uang Masuk:</strong> Rp {{ number_format($penjualan->uang_masuk, 2) }}</p>
                    <p><strong>Kembalian:</strong> Rp {{ number_format($penjualan->uang_kembalian, 2) }}</p>
                </div>
            </div>

            <h4 class="mt-4">Barang yang Dibeli</h4>
            <table class="table table-striped">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan->detailPenjualan as $detail)
                        <tr>
                            <td>{{ $detail->nama_barang ?? '-' }}</td> <!-- Sekarang ambil dari detail_penjualan -->
                            <td>{{ $detail->quantity ?? 0 }}</td>
                            <td>Rp {{ number_format($detail->subtotal_harga, 2) }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="{{ route('laporan.cetakpdf', $penjualan->id) }}" class="btn btn-danger" id="btnCetak">Cetak
                    PDF</a>
            </div>


        </div>
    </div>

    <script>
        document.getElementById("btnCetak").addEventListener("click", function(event) {
            event.preventDefault(); // Mencegah navigasi langsung

            var win = window.open("{{ route('laporan.cetakpdf', $penjualan->id) }}", "_blank");
            if (win) {
                setTimeout(() => {
                    win.print(); // Memicu cetak otomatis
                }, 2000); // Delay 2 detik agar PDF sempat termuat
            } else {
                alert("Popup diblokir oleh browser! Izinkan popup untuk mencetak.");
            }
        });
    </script>
@endsection
