@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800" data-aos="fade-right" data-aos-duration="1000">{{ __('Laporan Pesanan') }}</h1>

<!-- Filter Card -->
<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reports') }}" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir:</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            Pesanan yang Telah Selesai
            @if(request('start_date') || request('end_date'))
                <small class="text-muted">
                    ({{ request('start_date') ? date('d M Y', strtotime(request('start_date'))) : 'Awal' }} - 
                     {{ request('end_date') ? date('d M Y', strtotime(request('end_date'))) : 'Sekarang' }})
                </small>
            @endif
        </h6>
        <div>
            <button class="btn btn-info btn-sm" onclick="printReport()">
                <i class="fas fa-print"></i> Print Laporan
            </button>
            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($completedOrders->isEmpty())
            <div class="alert alert-info text-center" data-aos="fade-up" data-aos-duration="1000">
                <i class="fas fa-info-circle"></i> Belum ada pesanan yang selesai.
            </div>
        @else
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedOrders->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($completedOrders->sum('total_harga'), 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata per Pesanan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($completedOrders->avg('total_harga'), 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Item Terjual</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedOrders->sum(function($order) { return $order->items->sum('jumlah'); }) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Bank</th>
                            <th>Tanggal Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completedOrders as $order)
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
                                <span class="badge badge-success">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($order->bank)
                                    {{ $order->bank->nama_bank }}<br>
                                    <small class="text-muted">{{ $order->bank->nomor_rekening }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.order-detail', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        @if(!$completedOrders->isEmpty())
        $('#dataTable').DataTable({
            "order": [[ 5, "desc" ]], // Sort by date column
            "pageLength": 25,
            "language": {
                "emptyTable": "Tidak ada data pesanan yang tersedia",
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

    // Function to export to Excel
    function exportToExcel() {
        @if($completedOrders->isEmpty())
            Swal.fire({
                title: 'Tidak Ada Data',
                text: 'Tidak ada data pesanan untuk diekspor.',
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
            return;
        @endif

        // Get current filter parameters
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        // Build filename with date range
        let filename = 'laporan-pesanan';
        if (startDate) filename += '-dari-' + startDate;
        if (endDate) filename += '-sampai-' + endDate;
        filename += '-' + new Date().toISOString().slice(0, 10) + '.csv';
        
        // Simple CSV export dengan BOM untuk Excel
        let csv = '\uFEFF'; // BOM untuk UTF-8
        csv += 'Order ID,Customer,Email,Total Harga,Status,Bank,Tanggal Order\n';
        
        @foreach($completedOrders as $order)
        csv += '"#{{ $order->id }}","{{ addslashes($order->user->name) }}","{{ $order->user->email }}","Rp {{ number_format($order->total_harga, 0, ".", "") }}","{{ ucfirst(str_replace("_", " ", $order->status)) }}","{{ $order->bank ? addslashes($order->bank->nama_bank) : "-" }}","{{ $order->created_at->format("d M Y, H:i") }}"\n';
        @endforeach

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', filename);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        // Show success message
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil diekspor ke file CSV.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
    }

    // Function to print report
    function printReport() {
        @if($completedOrders->isEmpty())
            Swal.fire({
                title: 'Tidak Ada Data',
                text: 'Tidak ada data pesanan untuk dicetak.',
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
            return;
        @endif

        // Create print window with filtered data
        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            Swal.fire({
                title: 'Error!',
                text: 'Popup diblokir oleh browser. Silakan izinkan popup untuk mencetak laporan.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        let dateRange = '';
        if (startDate || endDate) {
            dateRange = ' (' + (startDate ? new Date(startDate).toLocaleDateString('id-ID') : 'Awal') + 
                       ' - ' + (endDate ? new Date(endDate).toLocaleDateString('id-ID') : 'Sekarang') + ')';
        }
        
        let htmlContent = `
            <html>
            <head>
                <title>Laporan Pesanan${dateRange}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                    h1 { text-align: center; color: #333; margin-bottom: 30px; }
                    .summary { display: flex; justify-content: space-around; margin: 20px 0; flex-wrap: wrap; }
                    .summary div { text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin: 5px; min-width: 150px; }
                    .summary h3 { margin: 0 0 10px 0; color: #007bff; }
                    .summary p { margin: 0; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
                    th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                    th { background-color: #f8f9fa; font-weight: bold; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    @media print { 
                        body { margin: 0; font-size: 10px; }
                        .no-print { display: none; }
                        table { font-size: 9px; }
                        .summary div { min-width: 120px; padding: 10px; }
                    }
                </style>
            </head>
            <body>
                <h1>Laporan Pesanan Happy Thrifting${dateRange}</h1>
                
                <div class="summary">
                    <div>
                        <h3>{{ $completedOrders->count() }}</h3>
                        <p>Total Pesanan</p>
                    </div>
                    <div>
                        <h3>Rp {{ number_format($completedOrders->sum('total_harga'), 0, '.', ',') }}</h3>
                        <p>Total Pendapatan</p>
                    </div>
                    <div>
                        <h3>Rp {{ $completedOrders->count() > 0 ? number_format($completedOrders->avg('total_harga'), 0, '.', ',') : '0' }}</h3>
                        <p>Rata-rata per Pesanan</p>
                    </div>
                    <div>
                        <h3>{{ $completedOrders->sum(function($order) { return $order->items->sum('jumlah'); }) }}</h3>
                        <p>Total Item Terjual</p>
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th width="8%">Order ID</th>
                            <th width="25%">Customer</th>
                            <th width="15%">Total Harga</th>
                            <th width="12%">Status</th>
                            <th width="20%">Bank</th>
                            <th width="20%">Tanggal Order</th>
                        </tr>
                    </thead>
                    <tbody>`;

        // Add table rows
        @foreach($completedOrders as $order)
        htmlContent += `
                        <tr>
                            <td class="text-center">#{{ $order->id }}</td>
                            <td>
                                <strong>{{ addslashes($order->user->name) }}</strong><br>
                                <small style="color: #666;">{{ $order->user->email }}</small>
                            </td>
                            <td class="text-right">Rp {{ number_format($order->total_harga, 0, '.', ',') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                            <td>{{ $order->bank ? addslashes($order->bank->nama_bank) : '-' }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>`;
        @endforeach

        htmlContent += `
                    </tbody>
                </table>
                
                <p style="margin-top: 30px; text-align: center; color: #666; font-size: 10px;">
                    Dicetak pada: ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID')}
                </p>
            </body>
            </html>
        `;

        printWindow.document.write(htmlContent);
        
        printWindow.document.close();
        printWindow.focus();
        
        // Add delay for content to load before printing
        setTimeout(() => {
            printWindow.print();
        }, 500);
    }

    // Print styles
    @media print {
        .sidebar, .navbar, .card-header button, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</script>
@endsection