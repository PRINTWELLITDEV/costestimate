<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
<!-- Sidebar -->
<aside class="app-sidebar">
    <div class="sidebar-brand">

        <a href="{{ url('/ce') }}" class="brand-link">
            <img src="{{ asset('uploads/img/costestimate.png') }}" alt="ce Logo" class="brand-image rounded-circle" />
            <span class="brand-text fw-bold">{{ config('app.name') }}</span>

        </a>
    </div>
    <div class="sidebar-brand">
        @if(auth()->user()->level == 1)
            <!-- <img src="{{ asset(auth()->user()->profile_pic_url) }}" alt="Super Admin Logo" class="brand-image rounded-circle" /> -->
            <span class="brand-text fw-light">
                Super Admin
            </span>
        @else
            <!-- <img src=" " alt="Site Logo" class="brand-image shadow rounded-circle" /> -->
            <span class="brand-text fw-light small">
            </span>
        @endif
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">

                <li class="nav-item">
                    <a href="{{ url('/ce') }}" class="nav-link{{ request()->is('ce') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-house-door-fill"></i>
                        <p>Home</p>
                    </a>
                </li>
                @if(auth()->user()->level == 1)
                <li class="nav-item has-treeview{{ request()->is('ce/sites*') || request()->is('ce/users*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-shield-fill"></i>
                        <p>
                            Administration
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/ce/sites') }}" class="nav-link{{ request()->is('ce/sites*') ? ' active' : '' }}">
                                <i class="nav-icon bi bi-buildings-fill"></i>
                                <p>Site Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/ce/users') }}" class="nav-link{{ request()->is('ce/users*') ? ' active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-item has-treeview{{ request()->is('ce/paper-types*') ||
                    request()->is('ce/vendors*') ||
                    request()->is('ce/stocks*') ||
                    request()->is('ce/paper-board-price*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-folder-fill"></i>
                        <p>
                            Paper / Boards
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/ce/paper-board-price') }}"
                                class="nav-link{{ request()->is('ce/paper-board-price*') ? ' active' : '' }}">
                                <i class="nav-icon bi bi-tag-fill"></i>
                                <p>Paper / Board Pricing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/ce/paper-types') }}"
                                class="nav-link{{ request()->is('ce/paper-types*') ? ' active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-ppt-fill"></i>
                                <p>Paper Types</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/ce/stocks') }}"
                                class="nav-link{{ request()->is('ce/stocks*') ? ' active' : '' }}">
                                <i class="nav-icon bi bi-layers-half"></i>
                                <p>Stocks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/ce/vendors') }}"
                                class="nav-link{{ request()->is('ce/vendors*') ? ' active' : '' }}">
                                <i class="nav-icon fas fa-store"></i>
                                <p>Vendors</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

</aside>