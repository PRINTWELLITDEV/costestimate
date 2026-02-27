<footer class="app-footer d-flex justify-content-between align-items-center">
    <strong>
        &copy; {{ date('Y') }}
        <a href="http://www.printwell.com.ph" class="pi-link">Printwell, Inc.</a>
    </strong>
    <div class="d-none d-sm-inline mx-2">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>
</footer>
