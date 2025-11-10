<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light" style="background: linear-gradient(135deg, var(--primary-light) 0%, var(--background-light) 50%, var(--white) 100%); min-height: 100vh;">

    <!-- Modern Login Section -->
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <!-- Back to Home Button -->
                <div class="text-center mb-4">
                    <a href="{{ route('index') }}" class="btn btn-outline-primary hover-lift">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>

                <!-- Login Card -->
                <div class="card shadow-custom border-0" data-aos="fade-up">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="text-gradient fw-bold mb-2">Selamat Datang!</h2>
                            <p class="text-muted">Masuk ke akun Happy Thrifting Anda</p>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                        <div class="alert alert-danger" data-aos="shake">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Oops!</strong> Ada beberapa masalah dengan input Anda.
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('user.login.submit') }}" method="POST" data-aos="fade-up" data-aos-delay="200">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Alamat Email
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       value="{{ old('email') }}" required placeholder="Masukkan email Anda"
                                       style="padding-left: 3rem;">
                                <i class="fas fa-envelope position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Kata Sandi
                                </label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                           required placeholder="Masukkan kata sandi Anda"
                                           style="padding-left: 3rem; padding-right: 3rem;">
                                    <i class="fas fa-lock position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                    <button type="button" class="btn btn-link position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        Ingat saya
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg hover-lift">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                </button>
                            </div>
                        </form>

                        <!-- Footer -->
                        <div class="text-center">
                            <p class="text-muted mb-3">Belum punya akun?</p>
                            <a href="{{ route('user.register') }}" class="btn btn-outline-primary hover-lift">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation feedback
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required]');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });
        });
    </script>

</body>

</html>