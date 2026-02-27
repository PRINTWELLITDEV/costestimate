@extends('ce/ce-layouts.app')
@section('title', 'Vendors | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Vendors</h1>
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
                                    <button type="button" id="btnAddVendor"
                                        class="btn btn-primary d-flex align-items-center me-2" data-bs-toggle="modal"
                                        data-bs-target="#addVendorModal">
                                        <i class="bi bi-plus-circle d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Vendor</span>
                                        <i class="bi bi-plus-circle d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="vendorSearch" class="form-control"
                                            placeholder="Search vendors...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="vendor-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                @if(auth()->user()->level == 1)
                                                    <th>Site</th>
                                                @endif
                                                <th>Group</th>
                                                <th>Vendor</th>
                                                <th>Vendor Name</th>
                                                <th>Currency</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vendorTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add vendor modal -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVendorLabel">Add New Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addVendorForm">
                        @csrf
                        <div class="mb-3">
                            @if(auth()->user()->level == 1)
                                <label for="site" class="form-label">Site</label>
                                <div class="input-group">
                                    <select name="Site" id="site" class="form-select" required>
                                        <option disabled selected>Select Site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->site }}" {{ old('site') == $site->site ? 'selected' : '' }}>
                                                {{ $site->site_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('Site') <div class="text-danger small">{{ $message }}</div> @enderror
                            @else
                                <input type="hidden" name="Site" value="{{ auth()->user()->site }}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="Vendnum" class="form-label">Vendor Number</label>
                            <input type="text" class="form-control" id="Vendnum" name="Vendnum" required>
                        </div>
                        <div class="mb-3">
                            <label for="Name" class="form-label">Vendor Name</label>
                            <input type="text" class="form-control" id="Name" name="Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="Currcode" class="form-label">Currency Code</label>
                            <!-- <input type="text" class="form-control" id="Currcode" name="Currcode" required> -->
                            <select name="Currcode" id="Currcode" class="form-select" required>
                                <option disabled selected>Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->Currcode }}" {{ old('Currcode') == $currency->Currcode ? 'selected' : '' }}>
                                        {{ $currency->Currcode }} - {{ $currency->CurrDesc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="group" class="form-label">Group</label>
                            <select name="Group" id="group" class="form-select" required>
                                <option disabled selected>Select Group</option>
                                <option value="IMPORTED">IMPORTED</option>
                                <option value="LOCAL">LOCAL</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveVendorBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit vendor modal -->
    <div class="modal fade" id="editVendorModal" tabindex="-1" aria-labelledby="editVendorModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <form action="{{ route('vendors.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVendorLabel">Vendor: <span class="fw-bold fs-5"
                                id="edit_vendnum_display"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editVendorForm">
                            @csrf
                            <input type="hidden" id="edit_site" name="Site">
                            <input type="text" class="form-control" id="edit_vendnum" name="Vendnum" hidden>
                            <div class="mb-3">
                                <label for="Name" class="form-label">Vendor Name</label>
                                <input type="text" class="form-control" id="edit_name" name="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="Currcode" class="form-label">Currency Code</label>
                                <!-- <input type="text" class="form-control" id="edit_currcode" name="Currcode" required> -->
                                <select name="Currcode" id="edit_currcode" class="form-select" required>
                                    <option disabled selected>Select Currency</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->Currcode }}" {{ old('Currcode') == $currency->Currcode ? 'selected' : '' }}>
                                            {{ $currency->Currcode }} - {{ $currency->CurrDesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_group" class="form-label">Vendor Group</label>
                                <select name="Group" id="edit_group" class="form-select" required>
                                    <option disabled selected>Select Group</option>
                                    <option value="IMPORTED">IMPORTED</option>
                                    <option value="LOCAL">LOCAL</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateVendorBtn">Update Vendor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection