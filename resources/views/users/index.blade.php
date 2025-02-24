@extends('layouts.main')

@section('title', 'Data Pelanggan')
@section('header')
    Daftar Pengguna
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



                <div class="card-header-action">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Pengguna</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tipe Pelanggan</th>
                                <th>Member Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($users->currentPage() - 1) * $users->perPage();
                            @endphp

                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div
                                            class="badge badge-{{ $user->role == 'pemilik' ? 'success' : ($user->role == 'kasir' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($user->role) }}
                                        </div>
                                    </td>
                                    <td>{{ $user->tipe_pelanggan ?? '-' }}</td>
                                    <td>{{ (int) $user->membership_poin }}</td>
                                    <td>
                                        @if ($user->trashed())
                                            <!-- Tombol Restore -->
                                            <form action="{{ route('users.restore', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Restore</button>
                                            </form>
                                        @else
                                            <!-- Tombol Edit dan Hapus -->
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-warning    btn-sm">Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                <nav class="d-inline-block">
                    {{ $users->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
@endsection
