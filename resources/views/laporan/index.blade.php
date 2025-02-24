@extends('layouts.main')

@section('title', 'Data Laporan')
@section('header')
    Laporan Transaksi
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="text-center">Laporan Transaksi Penjualan</h3>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

     
      <!-- Form Filter & Search -->
<form method="GET" action="{{ route('laporan.index') }}">
    <div class="row mb-3">
        <!-- Input Rentang Tanggal -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="text" id="daterange" name="daterange" class="form-control daterange"
                    placeholder="Pilih Rentang Tanggal" value="{{ request('daterange') }}">
            </div>
        </div>

        <!-- Input Pencarian -->
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kasir/Pembeli..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </div>

        <!-- Tombol Reset -->
        <div class="col-md-2">
            <a href="{{ route('laporan.index') }}" class="btn btn-warning w-100">
                <i class="fas fa-undo"></i> Reset
            </a>
        </div>

        <!-- Tombol Export Excel -->
        <div class="col-md-2">
            <a href="#" id="btnExportExcel" class="btn btn-success w-100">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
</form>


        
        
        <!-- Tabel Data -->
        <div class="table-responsive">
            @if ($penjualan->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-circle"></i> Tidak ada data transaksi yang ditemukan.
                </div>
            @else
            <table class="table table-bordered table-hover">
                <thead class="bg-secondary text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Pembeli</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        <th>Total Belanja</th>
                        <th>Diskon</th>
                        <th>PPN (12%)</th>
                        <th>Total Akhir</th>
                        <th>Uang Masuk</th>
                        <th>Kembalian</th>
                        <th>Barang Dibeli</th>
                        <th>Aksi</th> <!-- Tambahkan kolom aksi untuk tombol cetak PDF -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $index => $p)
                        <tr class="text-center align-middle">
                            <td>{{ $penjualan->firstItem() + $index }}</td>
                            <td>{{ $p->pengguna->name ?? 'Pengguna Umum' }}</td>
                            <td>{{ $p->kasir->name ?? '-' }}</td>

                            <td>{{ \Carbon\Carbon::parse($p->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}</td>
                            <td>Rp {{ number_format($p->total_pembelanjaan, 2) }}</td>
                            <td>{{ $p->diskon_persen ?? 0 }}% <br> 
                                (Rp {{ number_format(($p->total_pembelanjaan * ($p->diskon_persen ?? 0)) / 100, 2) }})
                            </td>
                            <td>Rp {{ number_format(($p->total_pembelanjaan - ($p->total_pembelanjaan * ($p->diskon_persen ?? 0)) / 100) * 0.12, 2) }}</td>
                            <td>Rp {{ number_format($p->total_akhir, 2) }}</td>
                            <td>Rp {{ number_format($p->uang_masuk, 2) }}</td>
                            <td>Rp {{ number_format($p->uang_kembalian, 2) }}</td>
                            <td>
                                @foreach ($p->detailPenjualan as $detail)
                                    {{ $detail->nama_barang ?? '-' }} ({{ $detail->quantity ?? 0 }}),
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('laporan.cetakpdf', $p->id) }}" class="btn btn-danger btn-cetak" data-id="{{ $p->id }}">
                                    <i class="fas fa-file-pdf"></i> Cetak PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @endif
        </div>

        <!-- Pagination -->
        <div class="card-footer text-right">
            <nav class="d-inline-block">
                {{ $penjualan->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#daterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Pilih',
                cancelLabel: 'Hapus',
                fromLabel: 'Dari',
                toLabel: 'Sampai',
                customRangeLabel: 'Custom',
                weekLabel: 'W',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],
                firstDay: 1
            },
            autoUpdateInput: false,  
            showDropdowns: true, 
            alwaysShowCalendars: true, 
            opens: 'center', 
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Tahun Ini': [moment().startOf('year'), moment().endOf('year')]
            }
        });
    
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });
    
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    
        $("#btnExportExcel").on("click", function(event) {
            event.preventDefault();
    
            let daterangeInput = $("#daterange").val().trim();
            let searchQuery = $("input[name='search']").val().trim();
            let url = "{{ route('laporan.export') }}";
            let params = [];
    
            if (daterangeInput && daterangeInput.includes(" - ")) {
                let dates = daterangeInput.split(" - ");
                if (dates.length === 2 && dates[0] !== "" && dates[1] !== "") {
                    params.push("start_date=" + encodeURIComponent(dates[0]));
                    params.push("end_date=" + encodeURIComponent(dates[1]));
                }
            }
    
            if (searchQuery !== "") {
                params.push("search=" + encodeURIComponent(searchQuery));
            }
    
            if (params.length > 0) {
                url += "?" + params.join("&");
            }
    
            window.location.href = url;
        });
    });
    
    // Tambahan: Cetak otomatis setelah membuka halaman cetak
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn-cetak").forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
    
                var url = this.getAttribute("href");
                var win = window.open(url, "_blank");
                
                if (win) {
                    setTimeout(() => {
                        win.print();
                    }, 2000);
                } else {
                    alert("Popup diblokir oleh browser! Izinkan popup untuk mencetak.");
                }
            });
        });
    });
    </script>
    
@endpush
