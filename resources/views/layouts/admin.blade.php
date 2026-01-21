<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laravel SB Admin 2">
    <meta name="author" content="Alejandro RH">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Animate.css for general animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- AOS (Animate on Scroll) for scroll-based animations -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">



    <!-- Fonts -->
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">


        <!-- Custom Elegant Style -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body {
                background-color: #F8F5F2;
                font-family: 'Poppins', sans-serif;
                color: #3B3B3B;
            }

            .sidebar {
                background: linear-gradient(180deg, #B08B64, #A47551);
                color: #fff;
            }

            .sidebar a {
                color: #F9F5EF;
                transition: all 0.3s ease;
            }

            .sidebar a:hover {
                background-color: rgba(255, 255, 255, 0.2);
                color: #FFF;
            }

            .nav-item.active>a {
                background-color: #FBEED7 !important;
                color: #A47551 !important;
                font-weight: 600;
                border-radius: 0.5rem;
            }

            .topbar {
                background-color: #FFF7F0 !important;
                box-shadow: 0 2px 10px rgba(164, 117, 81, 0.1);
            }

            .topbar h2 {
                color: #A47551;
                font-weight: 600;
            }

            .card {
                border-radius: 15px;
                border: none;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            footer {
                background-color: #FFF7F0;
                color: #A47551;
                font-weight: 500;
            }

            .btn-primary {
                background-color: #A47551;
                border-color: #A47551;
            }

            .btn-primary:hover {
                background-color: #8E6443;
                border-color: #8E6443;
            }

            .scroll-to-top {
                background-color: #A47551;
            }

            .scroll-to-top:hover {
                background-color: #8E6443;
            }
        </style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}" data-aos="fade-up" data-aos-duration="400">

                <div class="sidebar-brand-text mx-3">Happy Thrifting </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item  {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('products.index') ? 'active' : '' }} " data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('products.index') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>{{ __('Products') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('kategori.index') ? 'active' : '' }} " data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('kategori.index') }}">
                    <i class="fas fa-fw fa-list"></i>
                    <span>{{ __('Kategori') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('banks.*') ? 'active' : '' }} " data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('banks.index') }}">
                    <i class="fas fa-fw fa-university"></i>
                    <span>{{ __('Data Bank') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('orders.index') }}">
                    <i class="fa-solid fa-fw fa-store"></i>
                    <span>{{ __('Order') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('admin.reports') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>{{ __('Laporan Pesanan') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.cancelled-orders') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('admin.cancelled-orders') }}">
                    <i class="fas fa-fw fa-times-circle"></i>
                    <span>{{ __('Pesanan Dibatalkan') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('users.index') }}">
                    <i class="fa-solid fa-fw fa-users"></i>
                    <span>{{ __('Data User') }}</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}" data-aos="fade-right" data-aos-duration="500">
                <a class="nav-link " href="{{ route('admin.profile') }}">
                    <i class="fa-solid fa-fw fa-user"></i>
                    <span>{{ __('Profile') }}</span></a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h2 data-aos="fade" data-aos-duration="1000">Dashboard</h2>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>




                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ Auth::user()->name[0] }}"></figure>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('index') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Home') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('main-content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <a href="https://github.com/aleckrh" target="_blank">Happy Thrifting</a> {{ now()->year }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            offset: 200, // Animation duration
            easing: 'ease-in-out', // Easing for the animation
            once: true // Animation occurs only once
        });
    </script>



    @yield('scripts')



</body>

</html>