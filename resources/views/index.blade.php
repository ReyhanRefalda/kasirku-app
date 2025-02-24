@extends('layouts.main')

@section('header')
  Dashboard
@endsection



@section('content')
    <section class="section">
        <div class="row">
            <!-- Statistik Card -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Barang</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalBarang }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Kategori</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalKategori }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pelanggan</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalPengguna }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Stok Hampir Habis -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistics</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" height="182"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Stok Hampir Habis</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangHampirHabis as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->stok->sum('jumlah_stok') }}</td>

                                            <td>
                                                @php
                                                    $totalStok = $item->stok->sum('jumlah_stok'); // Menjumlahkan total stok
                                                @endphp
                                            
                                                @if($totalStok <= 0)
                                                    <div class="badge badge-danger">Habis</div>
                                                @elseif($totalStok <= $item->minimal_stok)
                                                    <div class="badge badge-warning">Hampir Habis</div>
                                                @else
                                                    <div class="badge badge-success">Tersedia</div>
                                                @endif
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                    @if($barangHampirHabis->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center">Semua stok barang masih cukup.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("myChart").getContext('2d');

        var data = {!! json_encode($data) !!};
        var labels = {!! json_encode($labels) !!};

        var maxValue = Math.max(...data);
        console.log("Max Value:", maxValue);

        // Menentukan batas atas sumbu Y dengan sedikit ruang di atas nilai tertinggi
        var suggestedMax = Math.ceil(maxValue * 1.2);
        console.log("Suggested Max:", suggestedMax);

        // Menyesuaikan stepSize agar tidak selalu kelipatan 2
        var stepSize;
        if (maxValue <= 100) {
            stepSize = 10; // Jika nilai kecil, gunakan stepSize kecil
        } else if (maxValue <= 500) {
            stepSize = 50; // Untuk nilai menengah
        } else {
            stepSize = Math.ceil(maxValue / 5); // Untuk nilai besar
        }

        console.log("Step Size:", stepSize);

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pendapatan Mingguan',
                    data: data,
                    borderWidth: 3,
                    borderColor: '#6777ef',
                    backgroundColor: 'rgba(103, 119, 239, 0.2)',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6777ef',
                    pointRadius: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        grid: {
                            display: true,
                            drawBorder: true,
                            lineWidth: 1
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: stepSize, // Step size disesuaikan otomatis
                            suggestedMax: suggestedMax
                        },
                        min: 0,
                        max: suggestedMax
                    },
                    x: {
                        grid: {
                            display: true,
                            color: '#fbfbfb',
                            lineWidth: 1
                        },
                        ticks: {
                            autoSkip: false,
                            maxTicksLimit: 10
                        }
                    }
                },
            }
        });
    });
</script>

@endpush
