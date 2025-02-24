@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center">
        <h1 class="text-danger display-1 fw-bold">403</h1>
        <h3 class="fw-bold">Akses Ditolak!</h3>
        <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">
            <i class="fas fa-sign-in-alt"></i> Kembali ke Login
        </a>
    </div>
</div>
@endsection
