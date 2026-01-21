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

    <style>
        /* 💕 Styling tambahan untuk halaman Tentang Kami */
        body {
            font-family: "Poppins", sans-serif;
            background-color: #fffaf7;
        }

        .about-section h2 {
            font-weight: 700;
            color: #6b4f36;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .about-section p {
            color: #4b3e35;
            line-height: 1.8;
            font-size: 1.05rem;
            text-align: justify;
        }

        .about-image {
            width: 300px;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
        }

        .about-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-custom {
            background-color: #A47551;
            border: none;
            color: #fff;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #8d6344;
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <!-- About Section -->
    <section id="about" class="about-section py-5" data-aos="fade-up">
        <div class="container">
            <div class="about-content text-center">
                <h2 data-aos="fade-down">Tentang Kami</h2>

                <!--Gambar kecil, di tengah, dengan animasi -->
                <div class="d-flex justify-content-center" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('assets/images/Thrifting.jpg') }}"
                        alt="Gambar Tim"
                        class="about-image img-fluid shadow">
                </div>

                <!-- ✨ Teks baru yang lebih elegan & seragam -->
                <p class="lead mt-4" data-aos="fade-up" data-aos-delay="500">
                    <strong>Happy Thrifting</strong> lahir dari semangat untuk menghadirkan gaya hidup berkelanjutan yang tetap stylish dan terjangkau.
                    Kami percaya bahwa setiap pakaian memiliki cerita, dan kami ingin membantu Anda menemukan potongan terbaik yang mencerminkan kepribadian unik Anda tanpa harus mengeluarkan biaya besar.
                </p>

                <p data-aos="fade-up" data-aos-delay="700">
                    Di balik setiap produk yang kami tawarkan, terdapat proses seleksi yang teliti dan penuh cinta.
                    Kami berkomitmen untuk menghadirkan barang-barang thrift berkualitas tinggi, bersih, layak pakai, dan tentunya penuh gaya.
                    Kepuasan pelanggan adalah prioritas kami, karena bagi kami, kepercayaan Anda adalah bagian terpenting dalam setiap perjalanan Happy Thrifting.
                </p>

                <p data-aos="fade-up" data-aos-delay="800">
                    Mari bergabung bersama kami untuk menjadikan thrift bukan sekadar tren, tetapi juga gaya hidup yang cerdas, ramah lingkungan, dan penuh makna.
                    Bersama <strong>Happy Thrifting</strong> — tampil keren, hemat, dan tetap peduli pada bumi 🌱💚
                </p>

                <a href="https://wa.me/6282375062833?text=Halo,%20saya%20mau%20bertanya!"
                    class="btn btn-lg btn-custom mt-4"
                    data-aos="fade-up" data-aos-delay="1000">
                    <i class="bi bi-whatsapp"></i> Hubungi Kami
                </a>
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