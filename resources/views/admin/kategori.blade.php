@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800 " data-aos="fade-right" data-aos-duration="1000">{{ __('Kategori') }}</h1>

<div class="card shadow mb-4 " data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addKategoriModal" data-aos="zoom-in" data-aos-duration="500">Tambah Kategori</button>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategories as $kategori)
                    <tr data-aos="fade-up" data-aos-duration="1000">
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td>
                            <img src="{{ $kategori->gambar_url }}" alt="{{ $kategori->nama_kategori }}" style="width: 100px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm update-button" data-bs-toggle="modal" data-bs-target="#editKategoriModal-{{ $kategori->id }}">Edit</a>
                            <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline delete-form">
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

<!-- Modal Edit Kategori -->
@foreach($kategories as $kategori)
<div class="modal fade" id="editKategoriModal-{{ $kategori->id }}" tabindex="-1" aria-labelledby="editKategoriModalLabel-{{ $kategori->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel-{{ $kategori->id }}">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Input fields -->
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" id="nama_kategori" value="{{ $kategori->nama_kategori }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="gambar_kategori" class="form-label">Gambar Kategori</label>
                        <input type="file" name="gambar_kategori" class="form-control" id="gambar_kategori">
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addKategoriModal" tabindex="-1" role="dialog" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                    </div>

                    <div class="form-group">
                        <label for="gambar_kategori">Gambar</label>
                        <input type="file" class="form-control-file" id="gambar_kategori" name="gambar_kategori" required>
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
                text: "Kategori ini akan dihapus!",
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