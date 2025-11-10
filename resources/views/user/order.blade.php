<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/order.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS CSS for animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    <div class="container mt-5" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="checkout-title" data-aos="fade-up" data-aos-duration="1000">Checkout</h2>

        @if($carts && count($carts) > 0)
        <div class="row checkout-summary">
            <!-- Left Column: User Information -->
            <div class="col-md-6" data-aos="fade-right" data-aos-duration="1000">
                <h3>User Information</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Name:</strong> {{ Auth::user()->name }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                    <li class="list-group-item"><strong>Alamat:</strong> {{ Auth::user()->alamat }}</li>
                </ul>
            </div>

            <!-- Right Column: Cart Summary -->
            <div class="col-md-6" data-aos="fade-left" data-aos-duration="1000">
                <h3>Cart Summary</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($total_harga, 0, ',', '.') }}</li>
                    <li class="list-group-item"><strong>Total Harga:</strong> Rp {{ number_format($total_harga, 0, ',', '.') }}</li>
                    <!-- Button Submit Order -->
                    <button type="button" id="pay-button" class="btn btn-success mt-3" data-aos="zoom-in" data-aos-duration="1000">Place Order</button>
                </ul>
            </div>

        </div>

        <!-- Cart Items Section -->
        <div class="row" data-aos="fade-up" data-aos-duration="1000">
            <div class="col-12 checkout-items">
                <h4>Cart Items</h4>
                <ul class="list-group">
                    @foreach($carts as $cart)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ $cart->products->gambar_url }}" alt="{{ $cart->products->nama_produk }}" class="product-image me-3" style="width: 60px; height: 60px; object-fit: cover;">

                            <div>
                                <strong>{{ $cart->products->nama_produk }}</strong>
                                <p>Quantity: {{ $cart->jumlah }}</p>
                            </div>
                        </div>
                        <div>
                            <strong>Harga</strong>
                            <p>Rp {{ number_format($cart->products->harga_produk * $cart->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @else
        <p>Your cart is empty.</p>
        @endif
    </div>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Config::$clientKey = env('MIDTRANS_CLIENT_KEY');"></script>
    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('payment success:', result);
                    // Lakukan redirect ke halaman order jika pembayaran berhasil
                    window.location.href = "{{ route('user.history') }}";
                },
                onPending: function(result) {
                    console.log('waiting for payment:', result);
                },
                onError: function(result) {
                    console.log('payment error:', result);
                }
            });
        };
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init();
    </script>
</body>

</html>