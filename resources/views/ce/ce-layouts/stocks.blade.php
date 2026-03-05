@extends('ce/ce-layouts.app')
@section('title', 'Stocks | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Stocks</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card p-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <a id="btnAddStock"
                                        class="btn btn-primary d-flex align-items-center me-2"
                                        href="{{ route('stocks.add') }}">
                                        <!-- data-bs-target="#addStockModal"> -->
                                        <i class="bi bi-plus-circle d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Stock</span>
                                        <i class="bi bi-plus-circle d-inline d-sm-none"></i>
                                    </a>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="stockSearch" class="form-control"
                                            placeholder="Search stocks...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="stock-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                @if(auth()->user()->level == 1)
                                                    <th width="5%">Site</th>
                                                @endif
                                                <th width="10%">Product Group</th>
                                                <th width="5%">Type</th>
                                                <th width="5%">GSM</th>
                                                <th width="5%">Caliper</th>
                                                <th width="5%">Pounds/Ream</th>
                                                <th width="5%">Chipboard No.</th>
                                                <th width="5%">Width</th>
                                                <th width="5%">Length</th>
                                                <th width="10%">Stock Code</th>
                                                <th width="20%">Stock Description</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="stockTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection