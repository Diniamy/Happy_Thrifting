@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800" data-aos="fade-right" data-aos-duration="1000">{{ __('Orderan') }}</h1>

<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Orderan</h6>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada pesanan yang perlu diproses saat ini.
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                        <tr data-aos="fade-up" data-aos-duration="1000">
                            <td>#{{ $order->id }}</td>
                            <td>
                                <div>
                                    <strong>{{ $order->user->name }}</strong><br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </div>
                            </td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'secondary',
                                        'waiting_payment' => 'warning',
                                        'waiting_confirmation' => 'info',
                                        'processing' => 'primary',
                                        'shipped' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if($order->status == 'waiting_confirmation')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Perlu Konfirmasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Cek apakah ada data atau tidak
        @if($orders->isEmpty())
            // Jika tidak ada data, jangan inisialisasi DataTable
            console.log('Tidak ada data order untuk ditampilkan');
        @else
            // Jika ada data, inisialisasi DataTable
            $('#dataTable').DataTable({
                "pageLength": 25,
                "order": [[ 4, "desc" ]], // Sort by tanggal column
                "language": {
                    "emptyTable": "Tidak ada data order yang tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entries",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "lengthMenu": "Tampilkan _MENU_ entries",
                    "loadingRecords": "Loading...",
                    "processing": "Processing...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir", 
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        @endif
    });

    // SweetAlert untuk konfirmasi delete
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Order ini akan dihapus!",
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