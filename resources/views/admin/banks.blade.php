@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800" data-aos="fade-right" data-aos-duration="1000">{{ __('Data Bank') }}</h1>

<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Bank</h6>
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addBankModal" data-aos="zoom-in" data-aos-duration="500">Tambah Bank</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banks as $bank)
                    <tr data-aos="fade-up" data-aos-duration="1000">
                        <td>{{ $bank->nama_bank }}</td>
                        <td>{{ $bank->nomor_rekening }}</td>
                        <td>{{ $bank->atas_nama }}</td>
                        <td>
                            <span class="badge badge-{{ $bank->is_active ? 'success' : 'danger' }}">
                                {{ $bank->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBankModal-{{ $bank->id }}">Edit</a>
                            <form action="{{ route('banks.destroy', $bank->id) }}" method="POST" class="d-inline delete-form">
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

<!-- Modal Edit Bank -->
@foreach($banks as $bank)
<div class="modal fade" id="editBankModal-{{ $bank->id }}" tabindex="-1" aria-labelledby="editBankModalLabel-{{ $bank->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('banks.update', $bank->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editBankModalLabel-{{ $bank->id }}">Edit Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bank" class="form-label">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ $bank->nama_bank }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_rekening" class="form-label">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="{{ $bank->nomor_rekening }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="atas_nama" class="form-label">Atas Nama</label>
                        <input type="text" name="atas_nama" class="form-control" value="{{ $bank->atas_nama }}" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active_{{ $bank->id }}" {{ $bank->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_{{ $bank->id }}">Aktif</label>
                        </div>
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

<!-- Modal Tambah Bank -->
<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankModalLabel">Tambah Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('banks.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_bank">Nama Bank</label>
                        <input type="text" class="form-control" name="nama_bank" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <input type="text" class="form-control" name="nomor_rekening" required>
                    </div>
                    <div class="form-group">
                        <label for="atas_nama">Atas Nama</label>
                        <input type="text" class="form-control" name="atas_nama" required>
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
                text: "Data bank ini akan dihapus!",
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