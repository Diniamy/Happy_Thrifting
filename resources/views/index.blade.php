<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/animasi.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    <!-- Modern Hero Section -->
    <section class="hero" data-aos="fade-up">
        <div class="container text-center">
            <h1 class="hero-title mb-3">
                Harga Hemat, <span class="highlight">Gaya Keren!</span>
            </h1>

            <p class="hero-subtitle lead mb-4">
                <strong>Temukan gaya terbaikmu</strong> di <strong><em>Happy Thrifting</em></strong>. <br>
                <strong>Karena tampil keren tak harus mahal.</strong>
            </p>




            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center mt-4">
                <a href="{{ route('product_page') }}"
                    class="btn btn-light btn-lg hover-lift"
                    data-aos="zoom-in" data-aos-delay="200">
                    <i class="fas fa-shopping-bag me-2"></i> Belanja Sekarang
                </a>
                <a href="{{ route('about') }}"
                    class="btn btn-outline-light btn-lg hover-lift"
                    data-aos="zoom-in" data-aos-delay="400">
                    <i class="fas fa-info-circle me-2"></i> Tentang Kami
                </a>
            </div>
        </div>
    </section>


    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @include('layouts.products')

    <!-- Modern Features Section -->
    <section class="features" data-aos="fade-up">
        <div class="container">
            <h2 class="text-center section-title text-gradient" data-aos="slide-up">Mengapa Memilih Kami?</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-custom hover-lift text-center">
                        <div class="card-body p-4">
                            <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="fas fa-shipping-fast" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="text-gradient fw-bold mb-3">Pengiriman Cepat</h4>
                            <p class="text-muted">Produk Anda akan dikirimkan dengan cepat melalui mitra pengiriman yang terpercaya ke seluruh Indonesia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-custom hover-lift text-center">
                        <div class="card-body p-4">
                            <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="fas fa-shield-alt" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="text-gradient fw-bold mb-3">Pembayaran Aman</h4>
                            <p class="text-muted">Kami memastikan tingkat keamanan tertinggi untuk setiap transaksi pembayaran online Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-custom hover-lift text-center">
                        <div class="card-body p-4">
                            <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="fas fa-gift" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="text-gradient fw-bold mb-3">Penawaran Eksklusif</h4>
                            <p class="text-muted">Nikmati diskon menarik dan promosi spesial setiap hari saat berbelanja bersama kami.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Testimonials Section -->
    <section class="testimonials" data-aos="fade-up">
        <div class="container">
            <h2 class="text-center section-title text-gradient" data-aos="slide-up">Apa Kata Pelanggan Kami</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-custom hover-lift">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-quote-left text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <p class="card-text mb-4 text-muted">"Produk-produk di sini luar biasa! Kualitas sangat baik dan pengiriman cepat. Saya menjadi pelanggan tetap sekarang."</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h5 class="text-gradient mb-0">Sari Indah</h5>
                                    <small class="text-muted">Pelanggan Tetap</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-custom hover-lift">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-quote-left text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <p class="card-text mb-4 text-muted">"Dukungan pelanggan sangat luar biasa. Mereka membantu saya dengan pesanan dan pengiriman saya!"</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h5 class="text-gradient mb-0">Budi Santoso</h5>
                                    <small class="text-muted">Pelanggan Puas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-custom hover-lift">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-quote-left text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <p class="card-text mb-4 text-muted">"Saya sangat suka berbelanja di sini! Diskonnya luar biasa, dan variasi produknya tak tertandingi."</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h5 class="text-gradient mb-0">Rina Melati</h5>
                                    <small class="text-muted">Pelanggan Senang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="https://wa.me/6282375062833?text=Halo,%20saya%20mau%20bertanya!" class="btn btn-primary btn-lg hover-lift shadow-custom" data-aos="zoom-in" data-aos-delay="400">
                    <i class="fab fa-whatsapp me-2"></i>Chat dengan Kami
                </a>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>


    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>

</html>