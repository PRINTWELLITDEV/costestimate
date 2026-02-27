<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/costestimate.png') }}">

    <link href="aos-master/dist/aos.css" rel="stylesheet"> 
    
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.min.css" rel="stylesheet">
</head>

<body class="@yield('body-class') layout-fixed fixed-header fixed-footer">

    <div id="app-wrapper">
        @if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'login')
            @include('partials.nav')
        @endif

        @if (Auth::check())
            @include('partials.aside')
        @endif
        
        <main class="app-main">
            @yield('content')
        </main>
        
        @include('partials.footer')

    </div>

    <script src="aos-master/dist/aos.js"></script>
    <script>
        window.appUrl = "{{ url('') }}";
        AOS.init({
            duration: 1200, // Duration of the animation
            once: true,     // Whether animation should happen only once - true is usually better for landing pages
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/columnControl.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
