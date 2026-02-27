@extends('layouts.app')
@section('title', config('app.name'))
@section('content')

    <div class="content-body">
        <div class="homepage-container">
            <!-- Left Side Visual -->
            <div class="homepage-visual" data-aos="fade-right">
                <div class="homepage-logo">{{ config('app.name') }}</div>
                <div class="homepage-tagline">
                    Where accuracy meets <strong>accountability</strong>
                </div>
                <p class="homepage-description">
                    Built to simplify costing and support confident business decisions.
                </p>
            </div>

            <!-- Right Side Login Form -->
            <div data-aos="fade-left">
                <div class="login-card">
                    <h5>Log in to {{ config('app.name') }}</h5>

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

                    <div class="divider">
                        <span>OR</span>
                    </div>

                    <div class="forgot-link">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    @if (Route::has('register'))
                        <div class="signup-link">
                            Don't have an account? <a href="{{ route('register') }}">Create Account</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



@endsection