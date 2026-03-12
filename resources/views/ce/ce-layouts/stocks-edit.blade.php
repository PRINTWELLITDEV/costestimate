@extends('ce/ce-layouts.app')
@section('title', 'Stocks | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card p-3">
                            <div class="card-header">
                                <h3 class="mb-0">
                                    <i class="fs-4 fas fa-edit me-2"></i> Edit Stock
                                </h3>
                            </div>
                            <div class="card-body">
                                <form id="editStockForm" action="{{ route('stocks.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="site" value="{{ $stock->Site }}">
                                    <input type="hidden" name="stockcode" value="{{ $stock->StockCode }}">

                                    <!-- Three Column Layout -->
                                    <div class="row mb-4">
                                        <!-- Column 1: Basic Information -->
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-info-circle me-2"></i>Basic Information
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="product_group" class="form-label fw-semibold">Product
                                                            Group</label>
                                                        <select name="product_group" id="product_group" class="form-select"
                                                            data-current="{{ old('product_group', $stock->ProductGroup) }}">
                                                            <option disabled>Select Product Group</option>
                                                            <option value="PAPER" {{ old('product_group', $stock->ProductGroup) == 'PAPER' ? 'selected' : '' }}>
                                                                Paper
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="ptype" class="form-label fw-semibold">Paper Type</label>
                                                        <select name="ptype" id="ptype" class="form-select"
                                                            data-current="{{ old('ptype', $stock->PType) }}">
                                                            <option disabled>Select Type</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Column 2: Technical Specifications -->
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-cog me-2"></i>Technical Specifications
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="gsm" class="form-label fw-semibold">GSM</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="gsm" name="gsm"
                                                                placeholder="0" value="{{ old('gsm', $stock->GSM) }}">
                                                            <span class="input-group-text">g/m²</span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="caliper" class="form-label fw-semibold">Caliper</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="caliper"
                                                                name="caliper" placeholder="0"
                                                                value="{{ old('caliper', $stock->Caliper) }}">
                                                            <span class="input-group-text">pts</span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pounds_ream"
                                                            class="form-label fw-semibold">Pounds/Ream</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="pounds_ream"
                                                                name="pounds_ream" placeholder="0"
                                                                value="{{ old('pounds_ream', $stock->PPR) }}">
                                                            <span class="input-group-text">lbs</span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-0">
                                                        <label for="chipboard_no" class="form-label fw-semibold">Chipboard
                                                            No.</label>
                                                        <input type="text" class="form-control" id="chipboard_no"
                                                            name="chipboard_no" placeholder="Enter chipboard number"
                                                            value="{{ old('chipboard_no', $stock->Cbnum) }}">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Column 3: Dimensions -->
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-warning text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-ruler me-2"></i>Dimensions
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="width" class="form-label fw-semibold">Width</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="width"
                                                                name="width" placeholder="0.00" step="0.01"
                                                                value="{{ old('width', $stock->Width == 0 ? '0' : $stock->Width) }}">
                                                            <span class="input-group-text">in</span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="length" class="form-label fw-semibold">Length</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="length"
                                                                name="length" placeholder="0.00" step="0.01"
                                                                value="{{ old('length', $stock->Length == 0 ? '0' : $stock->Length) }}">
                                                            <span class="input-group-text">in</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Information (Bottom Full Width) -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-barcode me-2"></i>Stock Information
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 mb-3">
                                                            <label for="stockCode" class="form-label fw-semibold">Stock
                                                                Code</label>
                                                            <input type="text" class="form-control bg-light" id="stockCode"
                                                                name="stock_code" placeholder="Auto-generated"
                                                                value="{{ old('stock_code', $stock->StockCode) }}" readonly>
                                                            <div class="form-text">
                                                                <i class="fas fa-magic me-1"></i>Automatically generated
                                                                based on specifications
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9 mb-0">
                                                            <label for="stockDescription"
                                                                class="form-label fw-semibold">Stock Description</label>
                                                            <input type="text" class="form-control" id="stockDescription"
                                                                name="stock_description" placeholder="Enter stock description"
                                                                value="{{ old('stock_description', $stock->StockDesc) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button onclick="window.location.href='{{ route('stocks.index') }}'"
                                                    type="button" class="btn btn-outline-secondary btn-lg px-4">
                                                    <i class="fas fa-times me-2"></i>Cancel
                                                </button>
                                                <button type="submit" id="updateStockbtn" class="btn btn-primary btn-lg px-4">
                                                    <i class="fas fa-save me-2"></i>Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection