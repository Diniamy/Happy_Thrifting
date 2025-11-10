<footer class="bg-primary text-white pt-4 mt-5 ">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Happy Thrifting</h5>
                <p>Sangat penting bagi pelanggan untuk memperhatikan proses adipiscing. Tetapi pada saat yang sama hal itu terjadi dengan susah payah dan kesakitan.</p>
            </div>
            <!-- Links -->
            <div class="col-lg-2 col-md-3 mb-4">
                <h5 class="text-uppercase mb-3">Tautan</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('index') }}" class="text-white text-decoration-none">Beranda</a></li>
                    <li><a href="{{ route('product_page') }}" class="text-white text-decoration-none">Produk</a></li>
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">Tentang Kami</a></li>
                    <li><a href="{{ route('user.cart') }}" class="text-white text-decoration-none">Keranjang</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Kontak Kami</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Lembaga Senggoro, Kec. Bengkalis, Kabupaten Bengkalis, Riau </p>
                <p><i class="fas fa-envelope me-2"></i>admin@gmail.com</p>
                <p><i class="fas fa-phone me-2"></i>0822-8806-5648</p>
            </div>
            <!-- Social Media -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Ikuti Kami</h5>
                <a href="https://www.facebook.com/paulinaasiregar?mibextid=ZbWKwL" class="btn btn-outline-light btn-floating m-1"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/6282288065648" class="btn btn-outline-light btn-floating m-1"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.instagram.com/happy_thrifting.bks?igsh=YXRvM3VydHI5bmpp" class="btn btn-outline-light btn-floating m-1"><i class="fab fa-instagram"></i></a>

            </div>
        </div>
        <hr class="mb-4">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">© {{ now()->year }} Happy Thrifting. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Floating Button for Admin Login -->
    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('index')) : route('admin.login') }}"
        class="btn btn-admin-login">
        <i class="bi bi-arrow-right"></i>
    </a>

</footer>