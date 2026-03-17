@extends('ce/ce-layouts.app')
@section('title', 'Paper / Board Price Calculator | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Paper / Board Price Calculator - Imported</h1>
                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success py-1 px-3 mb-0"
                                style="transition: opacity 0.7s;">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content-body">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col">
                        <div class="card p-3">
                            <div class="card-body">
                                <form id="pbpCalculatorForm" action="{{ route('paperboardprice.calculator.calculate') }}"
                                    data-user-level="{{ auth()->user()->level }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-8">
                                            @if (auth()->user()->level == 1)
                                                <div class="row g-2 align-items-center mb-2">
                                                    <label class="col-md-2 col-form-label text-md-end">Site</label>
                                                    <div class="col-md-3">
                                                        <select name="site" id="site" class="form-select form-select-sm">
                                                            <option value="" selected disabled>Select Site</option>
                                                            @foreach($sites as $site)
                                                                <option value="{{ $site->site }}">{{ $site->site_desc }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <input type="hidden" id="site_hidden" name="site"
                                                    value="{{ auth()->user()->site }}">
                                            @endif

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-md-2 col-form-label fw-bold text-md-end">RFQ</label>
                                                <div class="col-md-3">
                                                    <input type="text" id="rfq" tabindex="-1"
                                                        class="form-control form-control-sm fs-5 fw-bold bg-info" name="RFQ"
                                                        value="2025-12-P063">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Left side -->
                                        <div class="col-lg-8">
                                            

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-md-2 col-form-label text-md-end">Stock Code</label>
                                                <div class="col-md-3">
                                                    <select name="StockCode" id="stock_code"
                                                        class="form-select form-select-sm">
                                                        <option value="" selected disabled>
                                                            {{ auth()->user()->level == 1 ? 'Select Site First' : 'Select Stock' }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" id="stock_desc" tabindex="-1"
                                                        class="form-control form-control-sm bg-gray" name="StockDesc"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-md-2 col-form-label text-md-end">Paper Type</label>
                                                <div class="col-md-3">
                                                    <input type="text" id="p_type" tabindex="-1"
                                                        class="form-control form-control-sm bg-gray" name="PType" readonly>
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-md-2 col-form-label text-md-end">GSM</label>
                                                <div class="col-md-3">
                                                    <input type="number" id="gsm" step="0" tabindex="-1"
                                                        class="form-control form-control-sm text-end bg-gray" name="GSM"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-3">
                                                <label class="col-md-2 col-form-label text-md-end">Sheet Size (MM)
                                                    LxW</label>
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" id="sheet_mm_l" step="0.00001"
                                                            class="form-control text-end" name="SheetMM_L" placeholder="0">
                                                        <span class="input-group-text">X</span>
                                                        <input type="number" id="sheet_mm_w" step="0.00001"
                                                            class="form-control text-end" name="SheetMM_W" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-3">
                                                <label class="col-md-2 col-form-label text-md-end">Sheet Size (Inches)
                                                    LxW</label>
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" id="sheet_in_l" step="0.00001"
                                                            class="form-control text-end" name="SheetIN_L" placeholder="0">
                                                        <span class="input-group-text">X</span>
                                                        <input type="number" id="sheet_in_w" step="0.00001"
                                                            class="form-control text-end" name="SheetIN_W" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                <div class="offset-md-2 col-md-3">
                                                    <button type="submit" tabindex="-1"
                                                        class="btn btn-primary btn-sm w-100">PROCESS</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right side -->
                                        <div class="col-lg-4">
                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-6 col-form-label text-end">FX Rate</label>
                                                <div class="col-6">
                                                    <input type="number" step="0.000001" value="59.2"
                                                        class="form-control form-control-sm text-end" name="FXRate"
                                                        placeholder="0">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-6 col-form-label text-end">Duty % Rate</label>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" value="1"
                                                        class="form-control form-control-sm text-end"
                                                        name="DutyRate" placeholder="0">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-2">
                                                <label class="col-6 col-form-label text-end">Other Charges % Rate</label>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" value="7"
                                                        class="form-control form-control-sm text-end"
                                                        name="OtherChargesRate" placeholder="0">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-4">
                                                <label class="col-6 col-form-label text-end">Sheeting Cost Rate</label>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" value="1050"
                                                        class="form-control form-control-sm text-end" name="SheetingCost"
                                                        placeholder="0">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center">
                                                <label class="col-6 col-form-label text-end fw-semibold">COST PER
                                                    SHEET</label>
                                                <div class="col-6">
                                                    <input type="number" step="0.01"
                                                        class="form-control form-control-sm text-end fs-5 fw-bold text-bg-secondary text-white border-0"
                                                        name="CostPerSheet" placeholder="0" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle text-center mb-0"
                                        id="pbpCalculatorTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th rowspan="2">Select</th>
                                                <th rowspan="2" class="text-start" style="min-width:220px;">Vendor Name</th>
                                                <th rowspan="2">UM</th>
                                                <th rowspan="2">GSM</th>
                                                <th rowspan="2">C&amp;F Cost<br>per MT</th>
                                                <th colspan="2">Sheet Size</th>
                                                <th rowspan="2">Cost<br>per Sheet</th>

                                                <th colspan="7">Landed Cost Computation (per MT)</th>

                                                <th colspan="4">Conversion fr Rolls to Sheet / Costing per Sheet</th>
                                                <th rowspan="2">Add'l Sheeting<br>Charges?</th>
                                                <th rowspan="2">Exclude<br>Duty?</th>
                                            </tr>
                                            <tr>
                                                <th>Length</th>
                                                <th>Width</th>

                                                <th>C&amp;F Cost<br>in Pesos</th>
                                                <th>Duty<br>Rate</th>
                                                <th>Duty<br>Amount</th>
                                                <th>Other<br>Charges</th>
                                                <th>Landed<br>Cost</th>
                                                <th>Sheeting<br>Cost</th>
                                                <th>Sheeted<br>Cost</th>

                                                <th>Area in<br>Sq Inches</th>
                                                <th>Area in<br>Sq Meters</th>
                                                <th>Grams<br>per Sheet</th>
                                                <th>Sheets<br>per MT</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pbpCalculatorTbody">
                                            <tr>
                                                <td colspan="21" class="text-muted p-5">No result yet.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-start gap-2">
                                    <button type="button" id="updateCost" class="btn btn-success btn-md" disabled>
                                        <i class="bi bi-pen-fill"></i> Update Paper Cost
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection