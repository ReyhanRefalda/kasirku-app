@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('header')
    Edit Pengguna
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4>Edit Pengguna</h4>
                    </div>
                    <div class="card-body">

                        {{-- Pesan Sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif


                        {{-- Pesan Validasi --}}

                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="role">Role</label>
                                <select name="role" id="role"
                                    class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="pengguna" {{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password (Kosongkan jika tidak ingin mengubah) -->
                            <div class="form-group mt-3" id="passwordContainer">
                                <label for="password">Password (kosongkan jika tidak ingin mengubah)</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                        <i id="togglePasswordIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                            

                            <div class="form-group mt-3" id="passwordConfirmationContainer">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="passwordConfirm"
                                        class="form-control">
                                    <button type="button" id="togglePasswordConfirm" class="btn btn-outline-secondary">
                                        <i id="togglePasswordConfirmIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>


                            <!-- Tipe Pelanggan -->
                            <div class="form-group mt-3" id="tipePelangganContainer"
                                style="display: {{ $user->role == 'pengguna' ? 'block' : 'none' }};">
                                <label for="tipe_pelanggan">Tipe Pelanggan (hanya untuk pengguna)</label>
                                <select name="tipe_pelanggan" class="form-control">
                                    <option value="" {{ is_null($user->tipe_pelanggan) ? 'selected' : '' }}>-
                                    </option>
                                    <option value="1" {{ $user->tipe_pelanggan == '1' ? 'selected' : '' }}>Tipe 1
                                    </option>
                                    <option value="2" {{ $user->tipe_pelanggan == '2' ? 'selected' : '' }}>Tipe 2
                                    
                                </select>
                            </div>

                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
    let roleSelect = document.getElementById("role");
    let tipePelangganContainer = document.getElementById("tipePelangganContainer");

    function updateFormVisibility() {
        if (roleSelect && tipePelangganContainer) {
            if (roleSelect.value === "pengguna") {
                tipePelangganContainer.style.visibility = "visible";
                tipePelangganContainer.style.height = "auto";
            } else {
                tipePelangganContainer.style.visibility = "hidden";
                tipePelangganContainer.style.height = "0px";
            }
        }
    }

    if (roleSelect) {
        roleSelect.addEventListener("change", updateFormVisibility);
        updateFormVisibility();
    }

    // Fungsi untuk toggle password visibility
    function togglePassword(inputId, iconId) {
        let passwordInput = document.getElementById(inputId);
        let icon = document.getElementById(iconId);

        if (passwordInput) {
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; // Password ditampilkan
                if (icon) {
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye"); // Mata terbuka
                }
            } else {
                passwordInput.type = "password"; // Password disembunyikan
                if (icon) {
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash"); // Mata tertutup
                }
            }
        }
    }

    // Event listener untuk tombol toggle password (jika elemen ditemukan)
    let togglePasswordBtn = document.getElementById("togglePassword");
    let togglePasswordConfirmBtn = document.getElementById("togglePasswordConfirm");

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener("click", function () {
            togglePassword("password", "togglePasswordIcon");
        });
    }

    if (togglePasswordConfirmBtn) {
        togglePasswordConfirmBtn.addEventListener("click", function () {
            togglePassword("passwordConfirm", "togglePasswordConfirmIcon");
        });
    }
});

    </script>
@endsection
