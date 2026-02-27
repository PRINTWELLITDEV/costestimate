@php
    use Illuminate\Support\Facades\Storage;
    $user = $user ?? auth()->user(); // composer provides $user but fallback safe
    $siteDesc = $siteDesc ?? null;
@endphp
<nav class="app-header navbar navbar-expand bg-body sticky-top">
    <div class="container-fluid">

        {{-- =========================================================
        LEFT SIDE: BRAND, NAV LINKS, AND SIDEBAR TOGGLE
        ========================================================= --}}
        <ul class="navbar-nav">
            {{-- 1. Sidebar Toggle --}}
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>

            {{-- 2. Application Branding --}}
            <li class="nav-item d-none d-sm-block">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    IRMS
                </a>
            </li>

            {{-- 3. Standard Public Links (Home, Contact, About)
            These are hidden on XS screens where space is tight. --}}
            <li class="nav-item d-none d-md-block">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a class="nav-link" href="{{ url('/about') ?? '#' }}">About</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a class="nav-link" href="{{ url('/contact') ?? '#' }}">Contact</a>
            </li>
        </ul>

        {{-- =========================================================
        RIGHT SIDE: NOTIFICATIONS, FULLSCREEN, AUTH, USER DROPDOWN
        ========================================================= --}}
        <ul class="navbar-nav ms-auto">
            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge bg-warning rounded-pill">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                </div>
            </li>
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" id="fullscreenToggle" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!-- Small screen: icon-only login/logout -->
            <li class="nav-item d-md-none">
                @guest
                    <a class="nav-link" href="{{ route('login') }}" title="Login">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </a>
                @else
                    <a class="nav-link" href="#" title="Logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                @endguest
            </li>

            <!-- Auth link: shows Login for guests, Logout for authenticated users (visible on md+) -->
            <li class="nav-item d-none d-md-block">
                @guest
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                @else
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                        Logout
                    </a>
                @endguest
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{ $user && $user->profile_pic_path
    ? Storage::disk('public')->url($user->profile_pic_path)
    : asset('uploads/user-profile/noprofile.png') }}" class="user-image rounded-circle"
                        alt="{{ $user->userid ?? 'Guest' }}-img">
                    <span class="d-none d-md-inline">{{ $user->name ?? 'Guest' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="user-header text-center py-3">
                        <img src="{{ $user && $user->profile_pic_path
    ? Storage::disk('public')->url($user->profile_pic_path)
    : asset('uploads/user-profile/noprofile.png') }}" class="user-image rounded-circle shadow mb-2"
                            alt="{{ $user->userid ?? 'Guest User' }}-img" />
                        <div class="fw-bold">{{ $user->name ?? 'Guest User' }}</div>
                        <div class="text-muted small">User ID: {{ $user->userid ?? 'Guest ID' }}</div>
                        <small>{{ $siteDesc ?? 'N/A' }}</small>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="d-flex justify-content-center gap-3 p-2">
                        @auth
                            <a href="#" class="btn btn-outline-primary btn-sm">Profile</a>
                            <a href="#" class="btn btn-outline-danger btn-sm"
                                onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                                Log Out
                            </a>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                        @endguest
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Single logout form used by nav links -->
<form id="logout-form-top" class="text-danger" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
