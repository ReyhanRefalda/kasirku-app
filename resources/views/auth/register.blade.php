@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Register User</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="kasir">Kasir</option>
                                <option value="pengguna">Pengguna</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Tipe Pelanggan (Khusus Pengguna)</label>
                            <select name="tipe_pelanggan" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="1">Tipe 1</option>
                                <option value="2">Tipe 2</option>
                                <option value="3">Tipe 3</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-3">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
