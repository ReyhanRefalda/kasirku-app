@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="text-center mb-4">
        <h4>Login</h4>
    </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function togglePassword(inputId, iconId) {
                let passwordInput = document.getElementById(inputId);
                let icon = document.getElementById(iconId);
                
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye"); // Mata terbuka = password terlihat
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash"); // Mata tertutup = password tersembunyi
                }
            }

            document.getElementById("togglePassword").addEventListener("click", function() {
                togglePassword("password", "togglePasswordIcon");
            });
        });
    </script>
@endsection
