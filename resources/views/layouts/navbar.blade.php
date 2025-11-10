<!-- Modern Navbar -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{route('index')}}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="35" height="35" class="d-inline-block me-2 rounded-circle">
            <span class="text-gradient fw-bold">HAPPY THRIFTING</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('product_page') ? 'active' : '' }}" href="{{route('product_page')}}">
                        <i class="fas fa-shopping-bag me-1"></i>Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{route('about')}}">
                        <i class="fas fa-info-circle me-1"></i>Tentang Kami
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.cart') ? 'active' : '' }}" href="{{route('user.cart')}}">
                        <i class="fas fa-shopping-cart me-1"></i>Keranjang
                    </a>
                </li>

                @guest
                <li class="nav-item">
                    <a class="btn btn-light hover-lift ms-3" href="{{ route('user.login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Masuk
                    </a>
                </li>
                @endguest

                @auth
                @if(Auth::user()->role == 'user')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center ms-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fas fa-user text-dark"></i>
                        </div>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-custom" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                                <i class="fas fa-user-circle me-2 text-primary"></i>Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.history') }}">
                                <i class="fas fa-history me-2 text-info"></i>Riwayat Pesanan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="btn btn-light hover-lift ms-3" href="{{ route('user.login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Masuk
                    </a>
                </li>
                @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>