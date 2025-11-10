<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - {{ config('app.name') }}</title>
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

    @include('layouts.navbar')

    <div class="container py-5 mt-5" data-aos="fade-up">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment for Order #{{ $order->id }}</h4>
                    </div>
                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h5>Order Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->products->gambar_url }}" alt="{{ $item->products->nama_produk }}" style="width: 40px; height: 40px; object-fit: cover;" class="me-2 rounded">
                                                    <span>{{ $item->products->nama_produk }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_satuan * $item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="3" class="text-end">Total:</td>
                                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div class="mb-4">
                            <h5>Choose Payment Method</h5>
                            <p class="text-muted">Please transfer to one of the following bank accounts:</p>
                            
                            @if($banks->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No active bank accounts available for payment. Please contact admin.
                                </div>
                            @else
                                <div class="row">
                                    @foreach($banks as $bank)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">{{ $bank->nama_bank }}</h6>
                                                <p class="card-text mb-1">
                                                    <strong>Account Number:</strong> {{ $bank->nomor_rekening }}
                                                </p>
                                                <p class="card-text mb-0">
                                                    <strong>Account Name:</strong> {{ $bank->atas_nama }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Payment Form -->
                        @if(!$banks->isEmpty())
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5>Upload Payment Proof</h5>
                                <form action="{{ route('user.payment.process', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="bank_id" class="form-label">Select Bank Used for Transfer</label>
                                        <select name="bank_id" id="bank_id" class="form-select" required>
                                            <option value="">Choose Bank...</option>
                                            @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama_bank }} - {{ $bank->nomor_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bukti_transfer" class="form-label">Upload Transfer Receipt</label>
                                        <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control" accept="image/*" required>
                                        <div class="form-text">Please upload a clear image of your transfer receipt (JPG, PNG max 2MB)</div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('user.history') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-upload me-2"></i>Submit Payment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Preview image before upload
        document.getElementById('bukti_transfer').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview functionality here if needed
                };
                reader.readAsDataURL(file);
            }
        });

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#3085d6',
        });
        @endif
    </script>

</body>

</html>