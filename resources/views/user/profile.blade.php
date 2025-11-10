<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS for animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

</head>

<body>
    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Modern Profile Section -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Profile Header -->
                <div class="text-center mb-5" data-aos="fade-down">
                    <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-user" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="text-gradient fw-bold mb-2">Profil Saya</h2>
                    <p class="text-muted">Kelola informasi akun dan preferensi Anda</p>
                </div>

                <!-- Profile Card -->
                <div class="card shadow-custom border-0" data-aos="fade-up">
                    <div class="card-body p-5">
                        <!-- Success Message -->
                        @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm" data-aos="fade-in">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Berhasil!</strong> {{ session('success') }}
                        </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm" data-aos="shake">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Oops!</strong> Ada beberapa masalah dengan input Anda.
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Profile Form -->
                        <form action="{{ route('user.updateProfile') }}" method="POST" data-aos="fade-up" data-aos-delay="200">
                            @csrf
                            @method('PUT')

                            <!-- Personal Information Section -->
                            <div class="mb-5">
                                <h5 class="text-gradient fw-bold mb-4">
                                    <i class="fas fa-user-circle me-2"></i>Informasi Pribadi
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="fas fa-user me-2 text-primary"></i>Nama Lengkap
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" id="name" name="name" class="form-control form-control-lg" 
                                                   value="{{ Auth::user()->name }}" required placeholder="Masukkan nama lengkap"
                                                   style="padding-left: 3rem;">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>Alamat Email
                                        </label>
                                        <div class="position-relative">
                                            <input type="email" id="email" name="email" class="form-control form-control-lg" 
                                                   value="{{ Auth::user()->email }}" readonly
                                                   style="padding-left: 3rem; background-color: #f8f9fa;">
                                            <i class="fas fa-envelope position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                            <small class="form-text text-muted">Email tidak dapat diubah</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="alamat" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat Lengkap
                                    </label>
                                    <div class="position-relative">
                                        <textarea id="alamat" name="alamat" class="form-control form-control-lg" 
                                                  rows="3" required placeholder="Masukkan alamat lengkap Anda"
                                                  style="padding-left: 3rem; padding-top: 1rem;">{{ Auth::user()->alamat }}</textarea>
                                        <i class="fas fa-map-marker-alt position-absolute text-muted" style="left: 1rem; top: 1rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="mb-5">
                                <h5 class="text-gradient fw-bold mb-4">
                                    <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="password" class="form-label fw-bold">
                                            <i class="fas fa-lock me-2 text-primary"></i>Kata Sandi Baru
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" id="password" name="password" class="form-control form-control-lg" 
                                                   placeholder="Kosongkan jika tidak ingin mengubah"
                                                   style="padding-left: 3rem; padding-right: 3rem;">
                                            <i class="fas fa-lock position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                            <button type="button" class="btn btn-link position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Minimal 8 karakter (opsional)</small>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="password_confirmation" class="form-label fw-bold">
                                            <i class="fas fa-lock me-2 text-primary"></i>Konfirmasi Kata Sandi
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" 
                                                   placeholder="Ulangi kata sandi baru"
                                                   style="padding-left: 3rem; padding-right: 3rem;">
                                            <i class="fas fa-lock position-absolute text-muted" style="left: 1rem; top: 50%; transform: translateY(-50%);"></i>
                                            <button type="button" class="btn btn-link position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 hover-lift">
                                        <i class="fas fa-save me-2"></i>Perbarui Profil
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-lg w-100 hover-lift">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Account Stats -->
                        <div class="row mt-5 pt-4 border-top">
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Bergabung Sejak</h6>
                                <small class="text-muted">{{ Auth::user()->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-gradient-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Total Pesanan</h6>
                                <small class="text-muted">{{ Auth::user()->orders()->count() }} pesanan</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-gradient-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Status Member</h6>
                                <small class="text-muted">Member Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init();

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

        // Form Validation and Enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            const alamatTextarea = document.getElementById('alamat');
            
            // Real-time password matching validation
            if (passwordConfirm) {
                const validatePasswords = () => {
                    if (password.value && passwordConfirm.value) {
                        if (password.value !== passwordConfirm.value) {
                            passwordConfirm.setCustomValidity('Kata sandi tidak cocok');
                            passwordConfirm.classList.add('is-invalid');
                            passwordConfirm.classList.remove('is-valid');
                        } else {
                            passwordConfirm.setCustomValidity('');
                            passwordConfirm.classList.remove('is-invalid');
                            passwordConfirm.classList.add('is-valid');
                        }
                    } else {
                        passwordConfirm.setCustomValidity('');
                        passwordConfirm.classList.remove('is-invalid', 'is-valid');
                    }
                };

                password.addEventListener('input', validatePasswords);
                passwordConfirm.addEventListener('input', validatePasswords);
            }

            // Password strength indicator
            if (password) {
                password.addEventListener('input', function() {
                    const value = password.value;
                    password.classList.remove('is-invalid', 'is-valid');
                    
                    if (value.length >= 8) {
                        password.classList.add('is-valid');
                    } else if (value.length > 0 && value.length < 8) {
                        password.classList.add('is-invalid');
                    }
                });
            }

            // Auto-resize textarea
            if (alamatTextarea) {
                alamatTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
                
                // Initialize proper height
                alamatTextarea.style.height = alamatTextarea.scrollHeight + 'px';
            }

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds in case of errors
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
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
    </script>

    <style>
        .focused {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(216, 194, 178, 0.25);
        }
        
        .form-control.is-valid {
            border-color: #28a745;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .btn-link {
            color: var(--text-muted) !important;
            text-decoration: none;
        }
        
        .btn-link:hover {
            color: var(--primary-color) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
    </style>
</body>

</html>