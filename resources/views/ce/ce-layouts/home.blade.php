@extends('ce/ce-layouts.app')
@section('title', config('app.name') . ' - Home')
@section('content')

<div class="app-content-wrapper">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col mb-3 d-flex align-items-center">
                    <h1 class="d-inline-block mb-0 me-3">Home</h1>
                    @if(session('success'))
                        <div id="success-alert" class="alert alert-success py-1 px-3 mb-0" style="transition: opacity 0.7s;">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="app-content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-calculator display-1 text-muted"></i>
                                <h3 class="mt-3 text-muted">Cost Estimate Coming Soon</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection