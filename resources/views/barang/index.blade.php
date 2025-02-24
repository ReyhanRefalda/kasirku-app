@extends('layouts.main')

@section('header')
    Daftar Barang
@endsection

@section('content')
    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-header-action text-left">
                    <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Total Stok</th>
                                <th>Harga Jual</th>
                                <th>HPP</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok->sum('jumlah_stok') }}</td>
                                    <td>Rp{{ number_format($item->harga_jual, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-info" data-toggle="tooltip" title="Tipe 1: Rp{{ number_format($item->hpp_tipe1, 2, ',', '.') }} | Tipe 2: Rp{{ number_format($item->hpp_tipe2, 2, ',', '.') }} | Tipe 3: Rp{{ number_format($item->hpp_tipe3, 2, ',', '.') }}">
                                            Lihat HPP
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->deleted_at || $item->stok->where('tanggal_kedaluarsa', '>=', now())->count() == 0)
                                        <span class="badge bg-danger">Kedaluwarsa</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                    
                                    </td>
                                    <td>
                                        @if ($item->trashed())  
                                            <form action="{{ route('barang.restore', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn-sm">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            <a href="{{ route('barang.tambahStokForm', $item->id) }}" class="btn btn-success btn-sm">Tambah Stok</a>
                                            <a href="{{ route('barang.detail', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        @endif
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

@section('scripts')
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
