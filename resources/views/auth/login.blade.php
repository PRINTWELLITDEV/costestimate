@extends('layouts.app')

@section('body-class', 'login-body')

@section('content')

    <!-- <img src="{{ asset('uploads/img/login-visual.jpg') }}" alt="Login Background" class="login-bg-img" /> -->
    <div class="login-split-container" data-aos="fade-down">
        <div class="login-card">
            <div class="login-visual position-relative">
                <div class="login-app-logo">
                    <!-- <img src="{{ asset('uploads/img/irms-logo.png') }}" alt="IRMS Logo"> -->
                </div>
                <!-- <img class="login-visual-img" src="{{ asset('uploads/img/login-visual.jpg') }}" alt="Login Visual" /> -->
            </div>
            <div class="login-form-panel">
                <h1>Log in to {{ config('app.name') }}!</h1>
                <div class="mb-3 form-text">Inventory Rack Management System – Secure access for your inventory and rack operations.</div>
                <form action="{{ route('login.submit') }}" method="POST" id="LoginForm">
                    @csrf

                    <div class="mb-3">
                        <label for="userid" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="userid" name="userid"
                            value="{{ old('userid', $rememberedUserId ?? '') }}" placeholder="Enter your User ID"
                            required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your Password" required>
                    </div>

                    <button type="submit" id="nextButton" class="login-btn">Log In</button>
                </form>
            </div>
        </div>
    </div>
@endsection
