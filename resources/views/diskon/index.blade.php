@extends('layouts.main')

@section('title', 'Data Diskon')
@section('header')
    Data Diskon
@endsection

@section('content')


    <div class="section-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <a href="{{ route('diskon.create') }}" class="btn btn-primary">Tambah Diskon</a>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Diskon</th>
                                <th>Persentase</th>
                                <th>Min Pembelian</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diskon as $d)
                                <tr>
                                    <td>{{ $d->kode_diskon }}</td>
                                    <td>{{ $d->nama_diskon }}</td>
                                    <td>{{ $d->diskon_persen }}%</td>
                                    <td>Rp {{ number_format($d->min_pembelanjaan, 0, ',', '.') }}</td>
                                    <td>{{ date('d M Y', strtotime($d->tanggal_mulai)) }} -
                                        {{ date('d M Y', strtotime($d->tanggal_berakhir)) }}</td>
                                    <td>
                                        <span class="badge {{ $d->status ? 'badge-success' : 'badge-danger' }}">
                                            {{ $d->status ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($d->trashed())
                                            {{-- Tombol Restore --}}
                                            <form action="{{ route('diskon.restore', $d->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn-sm">Restore</button>
                                            </form>
                                        @else
                                            {{-- Tombol Edit & Hapus --}}
                                            <a href="{{ route('diskon.edit', $d->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('diskon.destroy', $d->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus diskon ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
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
