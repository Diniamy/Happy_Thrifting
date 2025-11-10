<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- AOS CSS for animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Modern History Section -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5" data-aos="fade-down">
                    <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-history" style="font-size: 2rem;"></i>
                    </div>
                    <h2 class="text-gradient fw-bold mb-2">Riwayat Pesanan</h2>
                    <p class="text-muted">Kelola dan pantau semua pesanan Anda dalam satu tempat</p>
                </div>

                @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-5" data-aos="fade-up">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px;">
                        <i class="fas fa-shopping-bag text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum Ada Riwayat Pesanan</h4>
                    <p class="text-muted mb-4">Anda belum memiliki pesanan apapun. Mulai berbelanja sekarang!</p>
                    <a href="{{ route('index') }}" class="btn btn-primary hover-lift">
                        <i class="fas fa-shopping-cart me-2"></i>Mulai Berbelanja
                    </a>
                </div>
                @else
                <!-- Orders Grid -->
                <div class="row">
                    @foreach($orders as $order)
                    <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card shadow-custom border-0 h-100 hover-lift">
                            <!-- Card Header -->
                            <div class="card-header bg-gradient-light border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">
                                            <i class="fas fa-receipt me-2 text-primary"></i>Order #{{ $order->id }}
                                        </h6>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }} WIB</small>
                                    </div>
                                    @php
                                        $statusConfig = match($order->status) {
                                            'pending' => ['class' => 'secondary', 'icon' => 'clock', 'text' => 'Menunggu'],
                                            'waiting_payment' => ['class' => 'warning', 'icon' => 'credit-card', 'text' => 'Menunggu Pembayaran'],
                                            'waiting_confirmation' => ['class' => 'info', 'icon' => 'hourglass-half', 'text' => 'Menunggu Konfirmasi'],
                                            'confirmed' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Dikonfirmasi'],
                                            'completed' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'Selesai'],
                                            'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Dibatalkan'],
                                            'processing' => ['class' => 'primary', 'icon' => 'cog', 'text' => 'Diproses'],
                                            'shipped' => ['class' => 'success', 'icon' => 'truck', 'text' => 'Dikirim'],
                                            default => ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown']
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusConfig['class'] }} px-3 py-2">
                                        <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>
                                        {{ $statusConfig['text'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <!-- Order Summary -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Total Harga:</span>
                                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Jumlah Item:</span>
                                        <span class="fw-semibold">{{ $order->items->sum('jumlah') }} item</span>
                                    </div>
                                </div>
                                
                                <!-- Status Messages -->
                                @if($order->status == 'cancelled' && $order->catatan_admin)
                                <div class="alert alert-danger border-0 shadow-sm py-2 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                        <div>
                                            <small class="fw-bold">Alasan Pembatalan:</small>
                                            <br><small>{{ $order->catatan_admin }}</small>
                                        </div>
                                    </div>
                                </div>
                                @elseif($order->status == 'confirmed' && $order->catatan_admin)
                                <div class="alert alert-success border-0 shadow-sm py-2 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <div>
                                            <small class="fw-bold">Catatan:</small>
                                            <br><small>{{ $order->catatan_admin }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('user.order-detail-user', $order->id) }}" class="btn btn-outline-primary btn-sm hover-lift flex-fill">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    
                                    @if($order->status == 'waiting_payment')
                                    <a href="{{ route('user.payment', $order->id) }}" class="btn btn-warning btn-sm hover-lift flex-fill">
                                        <i class="fas fa-credit-card me-1"></i>Bayar
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Count Info -->
                @if($orders->count() > 3)
                <div class="text-center mt-4" data-aos="fade-up">
                    <p class="text-muted">Menampilkan {{ $orders->count() }} pesanan</p>
                </div>
                @endif
                @endif

                <!-- Back to Home -->
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('index') }}" class="btn btn-outline-secondary hover-lift">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
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