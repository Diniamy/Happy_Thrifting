<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - {{ config('app.name') }}</title>
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

<body style="background: linear-gradient(135deg, #f5f1ed 0%, #faf8f5 50%, #ffffff 100%); min-height: 100vh;">

    <!-- Modern Register Section -->
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <!-- Back to Home Button -->
                <div class="text-center mb-4">
                    <a href="{{ route('index') }}" class="btn btn-outline-primary hover-lift">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>

                <!-- Register Card -->
                <div class="card shadow-custom border-0" data-aos="fade-up">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-plus" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="text-gradient fw-bold mb-2">Bergabung dengan Kami!</h2>
                            <p class="text-muted">Daftar untuk mendapatkan pengalaman berbelanja terbaik</p>
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

                        <!-- Register Form -->
                        <form action="{{ route('user.register.submit') }}" method="POST" data-aos="fade-up" data-aos-delay="200">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>Nama Lengkap
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                               value="{{ old('name') }}" required placeholder="Masukkan nama lengkap Anda"
                                               style="padding-left: 3rem;">
                                        <i class="fas fa-user position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Alamat Email
                                    </label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                               value="{{ old('email') }}" required placeholder="Masukkan alamat email Anda"
                                               style="padding-left: 3rem;">
                                        <i class="fas fa-envelope position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label for="alamat" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat Lengkap
                                    </label>
                                    <div class="position-relative">
                                        <textarea class="form-control form-control-lg" id="alamat" name="alamat" rows="3"
                                                  required placeholder="Masukkan alamat lengkap Anda"
                                                  style="padding-left: 3rem; padding-top: 1rem;">{{ old('alamat') }}</textarea>
                                        <i class="fas fa-map-marker-alt position-absolute text-muted" style="left: 1rem; top: 1rem;"></i>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2 text-primary"></i>Kata Sandi
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                               required placeholder="Masukkan kata sandi"
                                               style="padding-left: 3rem; padding-right: 3rem;">
                                        <i class="fas fa-lock position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                        <button type="button" class="btn btn-link position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2 text-primary"></i>Konfirmasi Kata Sandi
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" 
                                               required placeholder="Konfirmasi kata sandi"
                                               style="padding-left: 3rem; padding-right: 3rem;">
                                        <i class="fas fa-lock position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                        <button type="button" class="btn btn-link position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label text-muted" for="terms">
                                        Saya setuju dengan <a href="#" class="text-primary">Syarat & Ketentuan</a> dan <a href="#" class="text-primary">Kebijakan Privasi</a>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg hover-lift">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>

                        <!-- Footer -->
                        <div class="text-center">
                            <p class="text-muted mb-3">Sudah punya akun?</p>
                            <a href="{{ route('user.login') }}" class="btn btn-outline-primary hover-lift">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
    <script>
        // Password Toggle Function
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId === 'password' ? 'togglePasswordIcon' : 'togglePasswordConfirmIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            // Real-time password matching validation
            passwordConfirm.addEventListener('input', function() {
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Kata sandi tidak cocok');
                    passwordConfirm.classList.add('is-invalid');
                } else {
                    passwordConfirm.setCustomValidity('');
                    passwordConfirm.classList.remove('is-invalid');
                    passwordConfirm.classList.add('is-valid');
                }
            });

            // Password strength indicator
            password.addEventListener('input', function() {
                const value = password.value;
                password.classList.remove('is-invalid', 'is-valid');
                
                if (value.length >= 8) {
                    password.classList.add('is-valid');
                } else if (value.length > 0) {
                    password.classList.add('is-invalid');
                }
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftar...';
                submitBtn.disabled = true;
            });

            // Animate form fields on focus
            const formInputs = document.querySelectorAll('.form-control');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });

        // Auto-resize textarea
        document.getElementById('alamat').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>

    <style>
        :root {
            --primary-color: #d8c2b2;
            --text-muted: #6c757d;
        }
        
        .focused {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #d8c2b2;
            box-shadow: 0 0 0 0.2rem rgba(216, 194, 178, 0.25);
        }
        
        .form-control.is-valid {
            border-color: #28a745;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .btn-link {
            color: #6c757d !important;
            text-decoration: none;
        }
        
        .btn-link:hover {
            color: #d8c2b2 !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #d8c2b2, #8b7766);
        }

        .text-gradient {
            background: linear-gradient(135deg, #d8c2b2, #8b7766);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shadow-custom {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #d8c2b2, #8b7766);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #8b7766, #6b5b73);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: #d8c2b2;
            border-color: #d8c2b2;
        }

        .btn-outline-primary:hover {
            background-color: #d8c2b2;
            border-color: #d8c2b2;
            color: white;
        }
    </style>

</body>

</html>