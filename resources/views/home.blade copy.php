@extends('layouts.app')
@section('title', config('app.name'))

@section('body-class', 'home-body')

@section('content')
    {{-- Fullscreen background carousel --}}
    <div id="backgroundCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
        {{-- ... Carousel Inner Content ... --}}
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('uploads/img/rack_wallpaper_4.jpg') }}" loading="lazy"
                    class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploads/img/rack_wallpaper_5.jpg') }}" loading="lazy"
                    class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploads/img/rack_wallpaper_6.jpg') }}" loading="lazy"
                    class="d-block w-100 vh-100 object-fit-cover" alt="Warehouse 3">
            </div>
        </div>
    </div>

    <div class="glass-bg-overlay d-flex align-items-center justify-content-center text-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-8 text-center glass-panel p-5 rounded-4 justify-content-center"
                    data-aos="fade-up">

                    <h1 class="display-1 fw-bold mb-2 text-wrap" id="irms-home-text">
                        <!-- <span style="color: #154064;">I</span> -->
                        Inventory
                        <!-- <span style="color: #154064;">R</span> -->
                        Rack
                        <!-- <span style="color: #154064;">M</span> -->
                        Management
                        <!-- <span style="color: #154064;">S</span> -->
                        System
                    </h1>
                    <p class="lead d-flex flex-row flex-wrap flex-md-row flex-column align-items-center justify-content-center gap-2"
                    style="font-family: Arial, Helvetica, sans-serif; font-weight: bold;"
                    data-aos="zoom-in" data-aos-delay="1000">
                        <span class="d-flex align-items-center">
                            <i class="bi bi-check-circle bg-gradient-lime me-1"></i>Integrated Inventory
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-1"></i>Absolute Control
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-1"></i>Seamless Management
                        </span>
                    </p>

                    <div class="company-logos row d-flex justify-content-center align-items-center mb-4 gap-0">
                        <!-- <div class="col-sm-2" data-aos="fade-right" data-aos-delay="900">
                            <img src="{{ asset('uploads/sites-img/pi-logo2.png') }}" alt="printwell-logo"
                                 class="logo-img-small">
                        </div>
                        <div class="col-sm-4" data-aos="zoom-in" data-aos-delay="1000">
                            <img src="{{ asset('uploads/sites-img/fpc-logo.png') }}" alt="fpc-logo"
                                 class="logo-img-large">
                        </div>
                        <div class="col-sm-2" data-aos="fade-left" data-aos-delay="1100">
                            <img src="{{ asset('uploads/sites-img/pwpc-logo.png') }}" alt="pwpc-logo"
                                 class="logo-img-small">
                        </div> -->
                        @php
                            $logoCount = $sites->count();
                        @endphp
                        @foreach($sites as $site)
                            @if($site->logo_pic_url && file_exists(public_path($site->logo_pic_url)))
                                @php
                                    if ($loop->first) {
                                        $aos = 'fade-right';
                                        $delay = 900;
                                    } elseif ($loop->last) {
                                        $aos = 'fade-left';
                                        $delay = 900 + ($logoCount - 1) * 100;
                                    } else {
                                        $aos = 'zoom-in';
                                        $delay = 900 + $loop->index * 100;
                                    }
                                @endphp
                                <div class="col-4 col-sm-2" data-aos="{{ $aos }}" data-aos-delay="{{ $delay }}">
                                    <a href="{{ $site->site_link }}" target="_blank">
                                        <img src="{{ asset($site->logo_pic_url) }}" alt="{{ $site->rssite_desc ?? 'Company Logo' }}"
                                             class="logo-img-small">
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @guest
                        {{-- 3. Added the ID 'login-link' to the login button --}}
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 glass-button" id="login-link"
                            data-aos="fade-up" data-aos-delay="1100">Proceed to Login</a>
                    @else
                        {{-- ... Your logged-in content ... --}}
                        <div class="row g-4 justify-content-center mt-5 pt-3">
                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('inventory.index') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Inventory</h4>
                                        <p class="text-white-75 small">Access and manage all stock items and movements.</p>
                                        <i class="fas fa-cubes fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25"
                                            aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-5">
                                <a href="{{ route('dashboard') }}" class="card text-decoration-none glass-card rounded-4">
                                    <div class="card-body p-4 text-start position-relative">
                                        <h4 class="card-title mb-1 text-shadow">Dashboard</h4>
                                        <p class="text-white-75 small">Visualize key metrics and performance data.</p>
                                        <i class="fas fa-chart-bar fa-2x text-white position-absolute end-0 bottom-0 m-3 opacity-25"
                                            aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
