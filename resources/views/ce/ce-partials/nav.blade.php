<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <!-- <a class="nav-link" data-widget="pushmenu" href="#" role="button"> -->
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto bg-opacity-50">

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(auth()->user()->profile_pic_url) }}"
                            class="user-image rounded-circle border border-2 border-opacity-25 me-2"
                            alt="{{ auth()->user()->userid ?? 'Guest User' }}-img">
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Guest User' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="header">
                        <div class="user-name fw-bold">{{ auth()->user()->name ?? 'Guest User' }}</div>
                        <div class="user-subdescription">{{ auth()->user()->department ?? 'Unset Department' }} - {{ auth()->user()->position ?? 'Unset Position' }}</div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a href="{{ route('ce.userprofile', auth()->user()->userid) }}" class="dropdown-item">
                            <i class="bi bi-person"></i> Profile & Settings
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item sign-out" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                            <i class="bi bi-box-arrow-right"></i> Sign out
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    
                </ul>
            </li>
        </ul>
    </div>
</nav>

<form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
