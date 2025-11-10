<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS for animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Modern Order Details Section -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-4" data-aos="fade-down">
                    <a href="{{ route('user.history') }}" class="btn btn-outline-secondary hover-lift mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                    </a>
                    <h2 class="text-gradient fw-bold">Detail Pesanan</h2>
                </div>

                <!-- Order Summary Card -->
                <div class="card shadow-custom border-0 mb-4" data-aos="fade-up">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fas fa-receipt me-2"></i>Order #{{ $order->id }}
                                </h5>
                                <small class="opacity-75">{{ $order->created_at->format('d M Y, H:i') }} WIB</small>
                            </div>
                            @php
                                $statusConfig = match($order->status) {
                                    'pending' => ['class' => 'light', 'icon' => 'clock', 'text' => 'Menunggu'],
                                    'waiting_payment' => ['class' => 'warning', 'icon' => 'credit-card', 'text' => 'Menunggu Pembayaran'],
                                    'waiting_confirmation' => ['class' => 'info', 'icon' => 'hourglass-half', 'text' => 'Menunggu Konfirmasi'],
                                    'confirmed' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Dikonfirmasi'],
                                    'completed' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'Selesai'],
                                    'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Dibatalkan'],
                                    'processing' => ['class' => 'primary', 'icon' => 'cog', 'text' => 'Diproses'],
                                    'shipped' => ['class' => 'success', 'icon' => 'truck', 'text' => 'Dikirim'],
                                    default => ['class' => 'light', 'icon' => 'question', 'text' => 'Unknown']
                                };
                            @endphp
                            <span class="badge bg-{{ $statusConfig['class'] }} px-3 py-2 fs-6">
                                <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>
                                {{ $statusConfig['text'] }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-user me-2"></i>Informasi Pembeli
                                </h6>
                                <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                                <p class="text-muted mb-3">{{ $order->user->email }}</p>
                                <p class="text-muted">{{ $order->user->alamat }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran
                                </h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim:</span>
                                    <span class="text-success">Gratis</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Status Messages -->
                        @if($order->status == 'cancelled' && $order->catatan_admin)
                        <div class="alert alert-danger border-0 shadow-sm mt-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">Pesanan Dibatalkan</h6>
                                    <p class="mb-0">{{ $order->catatan_admin }}</p>
                                </div>
                            </div>
                        </div>
                        @elseif($order->status == 'confirmed' && $order->catatan_admin)
                        <div class="alert alert-success border-0 shadow-sm mt-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">Catatan dari Admin</h6>
                                    <p class="mb-0">{{ $order->catatan_admin }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="card shadow-custom border-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-gradient-light py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>Item Pesanan
                            <span class="badge bg-primary ms-2">{{ $order->items->count() }} item</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr data-aos="fade-up" data-aos-delay="{{ 300 + ($loop->index * 100) }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($item->products->gambar_produk)
                                                        <img src="{{ asset('storage/' . $item->products->gambar_produk) }}" 
                                                             alt="{{ $item->products->nama_produk }}" 
                                                             class="rounded shadow-sm"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $item->products->nama_produk }}</h6>
                                                    <small class="text-muted">{{ $item->products->kategori->nama_kategori ?? 'Umum' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-light text-dark px-3 py-2">{{ $item->jumlah }}x</span>
                                        </td>
                                        <td class="text-end align-middle">
                                            <span class="text-muted">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end align-middle pe-4">
                                            <strong class="text-primary">Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('user.history') }}" class="btn btn-outline-secondary hover-lift">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                        </a>
                        
                        @if($order->status == 'waiting_payment')
                        <a href="{{ route('user.payment', $order->id) }}" class="btn btn-warning hover-lift">
                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                        </a>
                        @endif
                        
                        <button class="btn btn-outline-primary hover-lift" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init();
    </script>
</body>

</html>