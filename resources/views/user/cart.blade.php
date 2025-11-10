<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- AOS CSS for animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/cart.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

</head>

<body>

    @include('layouts.navbar')

    <div class="container py-5 mt-5" data-aos="fade-up">
        <h2 class="text-center mb-4">Your Cart</h2>

        @if($carts && $carts->count() > 0)
        <div class="table-responsive" data-aos="zoom-in" data-aos-delay="200">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $cart)
                    <tr>
                        <td>{{ $cart->products->nama_produk }}</td>
                        <td>
                            @if($cart->products->gambar_produk)
                                <img src="{{ asset('storage/' . $cart->products->gambar_produk) }}" alt="{{ $cart->products->nama_produk }}" class="card-img-top" style="max-width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/no-image.png') }}" alt="No Image" class="card-img-top" style="max-width: 50px; height: 50px; object-fit: cover;">
                            @endif
                        </td>
                        <td>Rp {{ number_format($cart->products->harga_produk, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('user.cart.updateQuantity', $cart->products->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="input-group" style="width: 120px;">
                                    <input type="number" name="quantity" value="{{ $cart->jumlah }}" min="1" class="form-control form-control-sm">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>Rp {{ number_format($cart->products->harga_produk * $cart->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <!-- Tombol Delete -->
                            <form id="delete-form-{{ $cart->products->id }}" action="{{ route('user.cart.delete', $cart->products->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $cart->products->id }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Price -->
        <div class="row justify-content-end mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cart Summary</h5>
                        @php
                            $totalPrice = 0;
                            foreach($carts as $cart) {
                                $totalPrice += $cart->products->harga_produk * $cart->jumlah;
                            }
                        @endphp
                        <p class="card-text">
                            <strong>Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                        </p>
                        @if(Auth::check())
                        <a href="{{ route('user.cart.checkout') }}" class="btn btn-success w-100">Proceed to Checkout</a>
                        @else
                        <a href="{{ route('user.login') }}" class="btn btn-warning w-100">Login to Checkout</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <p class="text-center">Your cart is empty. <a href="{{ route('index') }}">Start shopping now</a>!</p>
        @endif
    </div>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 for Delete Confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // SweetAlert2 for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${itemId}`);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This item will be removed from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert2 success message for item deletion
        @if(session('success_delete'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('
            success_delete ') }}',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        @endif
    </script>

</body>

</html>