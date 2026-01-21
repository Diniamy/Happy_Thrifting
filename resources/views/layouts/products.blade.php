<section id="section-products" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 section-title text-gradient" data-aos="fade-up">Koleksi Produk Terbaik</h2>

        <!-- Kategori Modern -->
        <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-12">
                <h4 class="mb-4 text-gradient fw-bold">
                    <i class="fas fa-tags me-2"></i>Kategori Produk
                </h4>
                <div class="row g-3">
                    <!-- Kategori All -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('index') }}" class="category-card text-center text-decoration-none hover-lift">
                            <i class="fas fa-th-large mb-2 text-primary" style="font-size: 1.5rem;"></i>
                            <h6 class="category-name">Semua Produk</h6>
                        </a>
                    </div>
                    <!-- Looping kategori -->
                    @php
                    $currentRoute = Route::currentRouteName();
                    $icons = ['fas fa-tshirt', 'fas fa-shoe-prints', 'fas fa-hat-cowboy', 'fas fa-glasses', 'fas fa-gem', 'fas fa-shopping-bag'];
                    @endphp
                    @foreach($categories as $index => $category)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route($currentRoute, ['kategori' => $category->id]) }}" class="category-card text-center text-decoration-none hover-lift">
                            <i class="{{ $icons[$index % count($icons)] }} mb-2 text-primary" style="font-size: 1.5rem;"></i>
                            <h6 class="category-name">{{ $category->nama_kategori }}</h6>
                        </a>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        <!-- Produk Grid -->
        @if($products->isEmpty())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-info text-center shadow-custom" data-aos="fade-up">
                    <i class="fas fa-info-circle mb-3" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h4 class="text-gradient">Produk Tidak Tersedia</h4>
                    <p class="mb-0">Saat ini belum ada produk yang tersedia di kategori ini. Silakan coba kategori lain atau kembali lagi nanti!</p>
                </div>
            </div>
        </div>
        @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xl-4 col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index * 100) + 200 }}">
                <div class="card product-card hover-lift shadow-custom">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $product->gambar_url }}" class="card-img-top" alt="{{ $product->nama_produk }}">
                        @if($product->jumlah_produk <= 5)
                            <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-exclamation-triangle me-1"></i>Stok Terbatas!
                            </span>
                    </div>
                    @endif
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title text-dark fw-bold mb-2">{{ $product->nama_produk }}</h5>
                    <div class="mb-3">
                        <span class="price-tag">Rp {{ number_format($product->harga_produk, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-boxes me-1"></i>Stok: {{ $product->jumlah_produk }} tersedia
                    </p>

                    @if(Auth::check())
                    <div class="d-grid gap-2">
                        <div class="row g-2">
                            <div class="col-6">
                                <form action="{{ route('user.cart.add', ['id' => $product->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-100 hover-lift">
                                        <i class="fas fa-cart-plus me-1"></i>
                                        <span class="d-none d-sm-inline">Keranjang</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary w-100 hover-lift" onclick="openBuyNowModal({{ $product->id }}, '{{ addslashes($product->nama_produk) }}', {{ $product->harga_produk }}, {{ $product->jumlah_produk }}, '{{ $product->gambar_url }}')">
                                    <i class="fas fa-bolt me-1"></i>
                                    <span class="d-none d-sm-inline">Beli</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="d-grid">
                        <a href="{{ route('user.login') }}" class="btn btn-outline-primary hover-lift">
                            <i class="fas fa-sign-in-alt me-2"></i>Login untuk Membeli
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    </div>
</section>

<!-- Modal Konfirmasi Beli Sekarang -->
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white fw-bold" id="buyNowModalLabel">
                    <i class="fas fa-shopping-cart me-2"></i>Konfirmasi Pembelian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="text-center">
                            <img id="modal-product-image" src="" alt="Product Image" class="img-fluid rounded shadow-custom" style="height: 250px; object-fit: cover; width: 100%; border-radius: 15px;">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 id="modal-product-name" class="text-gradient mb-3 fw-bold"></h4>

                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Harga Satuan:</span>
                                <span class="price-tag">Rp <span id="modal-product-price"></span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-boxes me-1"></i>Stok Tersedia:
                                </span>
                                <span class="badge bg-info"><span id="modal-product-stock"></span> item</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="form-label fw-bold text-dark">
                                <i class="fas fa-calculator me-2"></i>Jumlah Pembelian:
                            </label>
                            <div class="input-group shadow-sm" style="max-width: 250px;">
                                <button class="btn btn-outline-primary" type="button" onclick="decreaseQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center fw-bold" id="quantity" value="1" min="1" max="" onchange="updateTotal()">
                                <button class="btn btn-outline-primary" type="button" onclick="increaseQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-3 bg-gradient-primary text-white rounded-3 text-center">
                            <h5 class="mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>Total Pembayaran:
                                <span class="fw-bold">Rp <span id="total-price"></span></span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary hover-lift" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-primary hover-lift animate-pulse" id="confirm-buy-btn" onclick="confirmPurchase()">
                    <i class="fas fa-bolt me-2"></i>Lanjutkan Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome JS -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let currentProductId = null;
    let currentProductPrice = 0;
    let currentProductStock = 0;

    function openBuyNowModal(productId, productName, productPrice, productStock, productImage) {
        currentProductId = productId;
        currentProductPrice = productPrice;
        currentProductStock = productStock;

        // Set modal content
        document.getElementById('modal-product-name').textContent = productName;
        document.getElementById('modal-product-price').textContent = formatNumber(productPrice);
        document.getElementById('modal-product-stock').textContent = productStock;
        document.getElementById('modal-product-image').src = productImage;
        document.getElementById('quantity').max = productStock;
        document.getElementById('quantity').value = 1;

        // Update total price
        updateTotal();

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('buyNowModal'));
        modal.show();
    }

    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let currentValue = parseInt(quantityInput.value);

        if (currentValue < currentProductStock) {
            quantityInput.value = currentValue + 1;
            updateTotal();
        }
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let currentValue = parseInt(quantityInput.value);

        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateTotal();
        }
    }

    function updateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const total = currentProductPrice * quantity;
        document.getElementById('total-price').textContent = formatNumber(total);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function confirmPurchase() {
        const quantity = parseInt(document.getElementById('quantity').value);

        if (quantity > currentProductStock) {
            alert('Jumlah yang dipilih melebihi stok yang tersedia!');
            return;
        }

        if (quantity < 1) {
            alert('Jumlah minimal adalah 1!');
            return;
        }

        // Redirect to buy now route with quantity parameter
        window.location.href = `/user/cart/buy-now/${currentProductId}?quantity=${quantity}`;
    }

    // Event listener for quantity input change
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value > currentProductStock) {
                    this.value = currentProductStock;
                } else if (value < 1) {
                    this.value = 1;
                }
                updateTotal();
            });
        }
    });
</script>