<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->

    <link href="/assets/css/about.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    <!-- About Section -->
    <section id="about" class="about-section py-5" data-aos="fade-up">
        <div class="container">
            <div class="about-content text-center">
                <h2 data-aos="fade-down">Tentang Kami</h2>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('assets/img/1.jpeg') }}" alt="Gambar Tim" class="about-image img-fluid rounded shadow">
                </div>
                <p class="lead mt-4" data-aos="fade-up" data-aos-delay="500">Kami adalah tim individu yang penuh semangat, yang berdedikasi untuk memberikan pengalaman e-commerce terbaik bagi pelanggan kami. Dengan pengalaman bertahun-tahun di industri ini, kami berusaha untuk menawarkan produk berkualitas tinggi, layanan pelanggan yang luar biasa, dan pengalaman belanja yang mulus. Misi kami adalah untuk memberdayakan para pembeli dan membuat belanja online menjadi lebih mudah dan menyenangkan.</p>
                <p data-aos="fade-up" data-aos-delay="700">Didirikan pada tahun 2024, platform e-commerce kami telah berkembang pesat, melayani pelanggan dari seluruh dunia. Kami percaya dalam membangun hubungan yang kuat dengan pelanggan kami dengan memahami kebutuhan dan preferensi mereka. Kepuasan Anda adalah prioritas kami, dan kami terus bekerja untuk meningkatkan layanan kami dan memperluas jajaran produk kami.</p>
                <a href="https://wa.me/6282375062833?text=Halo,%20saya%20mau%20bertanya!" class="btn btn-lg btn-custom btn-primary mt-4" data-aos="fade-up" data-aos-delay="900">Hubungi Kami</a>

            </div>
        </div>
    </section>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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