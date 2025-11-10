@extends('layouts.admin')

@section('main-content')

<!-- Page Heading -->
<!-- <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1> -->

@if (session('success'))
<div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session('status'))
<div class="alert alert-success border-left-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card border-left-success shadow h-100 py-2 animate__animated animate__fadeInUp ">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Profit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalProfit, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Produk Card Example -->
    <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card border-left-info shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card border-left-warning shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Users') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card border-left-warning shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Orders') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection