@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('header')
   Tambah Pengguna
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">

                        {{-- Menampilkan pesan sukses --}}
                      

                        {{-- Menampilkan pesan error validasi --}}
                       

                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-2">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-2">
                                <label>Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror"  id="roleSelect">
                                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="pengguna" {{ old('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Input Password -->
                            <div class="form-group mt-2" id="passwordContainer">
                                <label>Password</label>
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
                            
                            
                            <div class="form-group mt-2" id="passwordConfirmContainer">
                                <label>Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="passwordConfirm" class="form-control">
                                    <button type="button" id="togglePasswordConfirm" class="btn btn-outline-secondary">
                                        <i id="togglePasswordConfirmIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group mt-2" id="tipePelangganContainer" style="display: none;">
                                <label>Tipe Pelanggan</label>
                                <select name="tipe_pelanggan" class="form-control" id="tipePelangganSelect">
                                    <option value="">Pilih Tipe Pelanggan</option>
                                    <option value="1" {{ old('tipe_pelanggan') == '1' ? 'selected' : '' }}>Tipe 1</option>
                                    <option value="2" {{ old('tipe_pelanggan') == '2' ? 'selected' : '' }}>Tipe 2</option>
                                  
                                </select>
                            </div>

                            <input type="hidden" id="hiddenTipePelanggan" name="tipe_pelanggan" value="{{ old('tipe_pelanggan') }}">

                            <button type="submit" class="btn btn-primary btn-block mt-3">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    let roleSelect = document.getElementById("roleSelect");
    let passwordContainer = document.getElementById("passwordContainer");
    let passwordConfirmContainer = document.getElementById("passwordConfirmContainer");
    let tipePelangganContainer = document.getElementById("tipePelangganContainer");
    let tipePelangganSelect = document.getElementById("tipePelangganSelect");
    let hiddenTipePelanggan = document.getElementById("hiddenTipePelanggan");

    function updateForm() {
        if (roleSelect.value === "pengguna") {
            tipePelangganContainer.style.display = "block";
        } else {
            tipePelangganContainer.style.display = "none";
            hiddenTipePelanggan.value = ""; 
        }

        passwordContainer.style.display = "block";
        passwordConfirmContainer.style.display = "block";
    }

    roleSelect.addEventListener("change", updateForm);
    tipePelangganSelect.addEventListener("change", function() {
        hiddenTipePelanggan.value = this.value;
    });

    updateForm();

    // Toggle Password Visibility
    function togglePassword(inputId, iconId) {
        let passwordInput = document.getElementById(inputId);
        let icon = document.getElementById(iconId);
        
        if (passwordInput) {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Mata terbuka
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Mata tertutup
            }

            // Pastikan error tetap terlihat
            if (passwordInput.classList.contains("is-invalid")) {
                passwordInput.classList.add("is-invalid"); 
            }
        }
    }

    document.getElementById("togglePassword").addEventListener("click", function() {
        togglePassword("password", "togglePasswordIcon");
    });

    document.getElementById("togglePasswordConfirm").addEventListener("click", function() {
        togglePassword("passwordConfirm", "togglePasswordConfirmIcon");
    });
});

    </script>
    
@endsection
