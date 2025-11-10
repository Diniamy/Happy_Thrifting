@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800 " data-aos="fade-right" data-aos-duration="1000">{{ __('Produk') }}</h1>

<div class="card shadow mb-4 " data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addProductModal" data-aos="zoom-in" data-aos-duration="500">Tambah Produk</button>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr data-aos="fade-up" data-aos-duration="1000">
                        <td>{{ $product->nama_produk }}</td>
                        <td>Rp {{ number_format($product->harga_produk, 0, ',', '.') }}</td>
                        <td>{{ $product->jumlah_produk }}</td>
                        <td>
                            <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}" style="width: 100px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm update-button" data-bs-toggle="modal" data-bs-target="#editProductModal-{{ $product->id }}">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
@foreach($products as $product)
<div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel-{{ $product->id }}">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Input fields -->
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" id="nama_produk" value="{{ $product->nama_produk }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_produk" class="form-label">Kategori Produk</label>
                        <div class="input-group">
                            <select name="kategori_produk" class="form-control" id="kategori_produk" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                <option value="{{ $kategori->nama_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="harga_produk" class="form-label">Harga Produk</label>
                        <input type="number" name="harga_produk" class="form-control" id="harga_produk" value="{{ $product->harga_produk }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_produk" class="form-label">Jumlah Produk</label>
                        <input type="number" name="jumlah_produk" class="form-control" id="jumlah_produk" value="{{ $product->jumlah_produk }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="gambar_produk" class="form-label">Gambar Produk</label>
                        <input type="file" name="gambar_produk" class="form-control" id="gambar_produk">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_produk" class="form-label">Kategori Produk</label>
                        <div class="input-group">
                            <select name="kategori_produk" class="form-control" id="kategori_produk" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                <option value="{{ $kategori->nama_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="harga_produk">Harga</label>
                        <input type="number" class="form-control" id="harga_produk" name="harga_produk" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_produk">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah_produk" name="jumlah_produk" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar_produk">Gambar</label>
                        <input type="file" class="form-control-file" id="gambar_produk" name="gambar_produk" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    // SweetAlert untuk konfirmasi delete
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Produk ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert untuk konfirmasi Simpan Perubahan
    document.querySelectorAll('.modal form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Perubahan akan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert untuk pesan sukses
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#3085d6',
    });
    @endif
</script>
@endsection