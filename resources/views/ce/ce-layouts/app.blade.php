<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/img/costestimate.png') }}">

    @vite([
        'resources/css/ce.css',
        'resources/js/ce/ce.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/columncontrol/1.2.0/css/columnControl.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Page-specific CSS --}}
    @stack('styles')
</head>

<!-- <body class="layout-fixed fixed-header fixed-footer sidebar-mini sidebar-expand-*"> -->
<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg sidebar-collapse sidebar-mini bg-body-tertiary app-loaded">
    <div class="app-wrapper">
        {{-- Navbar --}}
        @include('ce.ce-partials.nav')

        {{-- Sidebar --}}
        @include('ce.ce-partials.aside')

        {{-- Main Content --}}
        <main class="app-main" id="main" tabindex="-1">
            @include('ce.ce-partials.breadcrumb')
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('ce.ce-partials.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/columncontrol/1.1.0/js/columnControl.dataTables.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.appUrl = "{{ url('') }}";
        window.sessionCheckUrl = "{{ url('/ce/session') }}";
        window.loginUrl = "{{ route('login') }}";

        window.sessionSuccess = @json(session('success'));
        window.sessionError = @json($errors->first());
    </script>

    {{-- Page-specific JS --}}
    @stack('scripts')
</body>

</html>