@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800" data-aos="fade-right" data-aos-duration="1000">{{ __('Pesanan Dibatalkan') }}</h1>

<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-danger">Daftar Pesanan yang Dibatalkan</h6>
        <div>
            <button class="btn btn-info btn-sm" onclick="printCancelledReport()">
                <i class="fas fa-print"></i> Print Laporan
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($cancelledOrders->isEmpty())
            <div class="alert alert-info text-center" data-aos="fade-up" data-aos-duration="1000">
                <i class="fas fa-info-circle"></i> Tidak ada pesanan yang dibatalkan.
            </div>
        @else
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Dibatalkan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelledOrders->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Potensi Kerugian</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($cancelledOrders->sum('total_harga'), 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Rata-rata Pembatalan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($cancelledOrders->avg('total_harga'), 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancelled Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Bank Transfer</th>
                            <th>Alasan Pembatalan</th>
                            <th>Tanggal Dibatalkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cancelledOrders as $order)
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
                                @if($order->bank)
                                    {{ $order->bank->nama_bank }}<br>
                                    <small class="text-muted">{{ $order->bank->nomor_rekening }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->catatan_admin)
                                    <small class="text-muted">{{ Str::limit($order->catatan_admin, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="confirmDelete({{ $order->id }})"
                                        title="Hapus Permanen">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pesanan ini secara permanen?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Data yang sudah dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 5, "desc" ]], // Sort by date column
            "pageLength": 25
        });
    });

    function confirmDelete(orderId) {
        document.getElementById('deleteForm').action = '/admin/orders/' + orderId;
        $('#deleteModal').modal('show');
    }

    function printCancelledReport() {
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <html>
            <head>
                <title>Laporan Pesanan Dibatalkan</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { text-align: center; color: #333; }
                    .summary { display: flex; justify-content: space-around; margin: 20px 0; }
                    .summary div { text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .text-right { text-align: right; }
                    @media print { 
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h1>Laporan Pesanan Dibatalkan - Happy Thrifting</h1>
                
                <div class="summary">
                    <div>
                        <h3>{{ $cancelledOrders->count() }}</h3>
                        <p>Total Dibatalkan</p>
                    </div>
                    <div>
                        <h3>Rp {{ number_format($cancelledOrders->sum('total_harga'), 0, ',', '.') }}</h3>
                        <p>Potensi Kerugian</p>
                    </div>
                    <div>
                        <h3>Rp {{ number_format($cancelledOrders->avg('total_harga'), 0, ',', '.') }}</h3>
                        <p>Rata-rata Pembatalan</p>
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Bank</th>
                            <th>Alasan</th>
                            <th>Tanggal Dibatalkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelledOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}<br><small>{{ $order->user->email }}</small></td>
                            <td class="text-right">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $order->bank ? $order->bank->nama_bank : '-' }}</td>
                            <td>{{ $order->catatan_admin ? Str::limit($order->catatan_admin, 30) : '-' }}</td>
                            <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <p style="margin-top: 30px; text-align: center; color: #666;">
                    Dicetak pada: ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID')}
                </p>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }

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