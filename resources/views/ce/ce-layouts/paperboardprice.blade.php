@extends('ce/ce-layouts.app')
@section('title', 'Paper Board Price | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Paper Board Price</h1>
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
                                    <button type="button" id="btnAddPricing"
                                        class="btn btn-primary d-flex align-items-center me-2" data-bs-toggle="modal"
                                        data-bs-target="#addPricingModal">
                                        <i class="bi bi-plus-circle d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Pricing</span>
                                        <i class="bi bi-plus-circle d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="pricingSearch" class="form-control" placeholder="Search...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="pricing-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                @if(auth()->user()->level == 1)
                                                    <th>Site</th>
                                                @endif
                                                <th>Group</th>
                                                <th>Paper Type</th>
                                                <th>Vendor</th>
                                                <th>Item Code</th>
                                                <th>Effective Date</th>
                                                <th>Currency</th>
                                                <th>Price/MT</th>
                                                <th>Price/Sheet</th>
                                                <th>Price/Pound</th>
                                                <th>Price/Bale</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pricingTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add pricing modal -->
    <div class="modal fade" id="addPricingModal" tabindex="-1" aria-labelledby="addPricingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPricingLabel">Add New Paper Board Pricing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPricingForm" action="{{ route('paperboardprice.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
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
                            @else
                                <input type="hidden" id="site" name="Site" value="{{ auth()->user()->site }}">
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group" class="form-label">Group</label>
                                    <select name="Group" id="group" class="form-select" required>
                                        <option disabled selected>Select Group</option>
                                        <option value="IMPORTED">IMPORTED</option>
                                        <option value="LOCAL">LOCAL</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="vendor" class="form-label">Vendor</label>
                                    <select name="Vendor" id="vendor" class="form-select" required>
                                        <option disabled selected>Select Vendor</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="currcode" class="form-label">Currency</label>
                                    <input type="text" class="form-control" id="currcode" name="Currcode" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ptype" class="form-label">Paper Type</label>
                                    <select name="PType" id="ptype" class="form-select" required>
                                        <option disabled selected>Select Paper Type</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="itemcode" class="form-label">Item Code</label>
                                    <select name="ItemCode" id="itemcode" class="form-select" required>
                                        <option disabled selected>Select Item</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="effectivedate" class="form-label">Effective Date</label>
                                    <input type="date" class="form-control" id="effectivedate" name="EffectiveDate"
                                        required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price_mt" class="form-label">Price/MT</label>
                                <input type="number" step="0.01" class="form-control" id="price_mt" name="Price_MT">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price_sheet" class="form-label">Price/Sheet</label>
                                <input type="number" step="0.01" class="form-control" id="price_sheet" name="Price_Sheet">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price_pound" class="form-label">Price/Pound</label>
                                <input type="number" step="0.01" class="form-control" id="price_pound" name="Price_Pound">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price_bale" class="form-label">Price/Bale</label>
                                <input type="number" step="0.01" class="form-control" id="price_bale" name="Price_Bale">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePricingBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editPricingModal" tabindex="-1" aria-labelledby="editPricingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPricingLabel">Edit Paper Board Pricing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPricingForm" action="{{ route('paperboardprice.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <input type="hidden" name="Site" id="edit_site">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_group" class="form-label">Group</label>
                                    <select name="Group" id="edit_group" class="form-select" required>
                                        <option disabled selected>Select Group</option>
                                        <option value="IMPORTED">IMPORTED</option>
                                        <option value="LOCAL">LOCAL</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_vendor" class="form-label">Vendor</label>
                                    <select name="Vendor" id="edit_vendor" class="form-select" required>
                                        <option disabled selected>Select Vendor</option>

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_currcode" class="form-label">Currency</label>
                                    <input type="text" class="form-control" id="edit_currcode" name="Currcode" readonly>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_ptype" class="form-label">Paper Type</label>
                                    <select name="PType" id="edit_ptype" class="form-select" required>
                                        <option disabled selected>Select Paper Type</option>

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_itemcode" class="form-label">Item Code</label>
                                    <select name="ItemCode" id="edit_itemcode" class="form-select" required>
                                        <option disabled selected>Select Item</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_effectivedate" class="form-label">Effective Date</label>
                                    <input type="date" class="form-control" id="edit_effectivedate" name="EffectiveDate"
                                        required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_price_mt" class="form-label">Price/MT</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price_mt" name="Price_MT">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_price_sheet" class="form-label">Price/Sheet</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price_sheet"
                                    name="Price_Sheet">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_price_pound" class="form-label">Price/Pound</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price_pound"
                                    name="Price_Pound">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_price_bale" class="form-label">Price/Bale</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price_bale"
                                    name="Price_Bale">
                            </div>
                        </div>
                        <!-- Similar input fields for Group, PType, Vendor, ItemCode, EffectiveDate, Currcode, Price_MT, Price_Sheet, Price_Pound, Price_Bale -->
                        <!-- You can replicate the same structure as in the add pricing modal with appropriate IDs and names for the edit form -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updatePricingBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection