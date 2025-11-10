@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Detail Order #{{ $order->id }}</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informasi Order</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-{{ $order->status == 'confirmed' ? 'success' : ($order->status == 'waiting_confirmation' ? 'warning' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </p>
                <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                
                @if($order->bank)
                <p><strong>Bank Transfer:</strong> {{ $order->bank->nama_bank }} - {{ $order->bank->nomor_rekening }}</p>
                @endif
            </div>
            
            <div class="col-md-6">
                @if($order->bukti_transfer)
                <div class="mb-3">
                    <strong>Bukti Transfer:</strong><br>
                    <img src="{{ asset('storage/' . $order->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid" style="max-width: 300px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                @endif
                
                @if($order->catatan_admin)
                <div class="alert alert-{{ $order->status == 'cancelled' ? 'danger' : 'info' }}">
                    <strong>{{ $order->status == 'cancelled' ? 'Alasan Pembatalan:' : 'Catatan Admin:' }}</strong><br>
                    {{ $order->catatan_admin }}
                </div>
                @endif
            </div>
        </div>
        
        @if($order->status == 'waiting_confirmation' && $order->bukti_transfer)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h6>Konfirmasi Pembayaran</h6>
                <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="catatan_admin">Catatan Admin (Opsional):</label>
                        <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mr-2" onclick="return confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?')">
                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times"></i> Tolak Pembayaran
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Item Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Gambar</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->products->nama_produk }}</td>
                        <td>
                            <img src="{{ $item->products->gambar_url }}" alt="{{ $item->products->nama_produk }}" style="width: 100px; height: 80px; object-fit: cover;">
                        </td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_admin_reject">Alasan Penolakan:</label>
                        <textarea name="catatan_admin" id="catatan_admin_reject" class="form-control" rows="4" placeholder="Berikan alasan penolakan pembayaran..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection