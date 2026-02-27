@extends('ce/ce-layouts.app')
@section('title', 'Paper Types | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Paper Types</h1>
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
                                    <button type="button" id="btnAddPType"
                                        class="btn btn-primary d-flex align-items-center me-2" data-bs-toggle="modal"
                                        data-bs-target="#addPTypeModal">
                                        <i class="bi bi-plus-circle d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Paper Type</span>
                                        <i class="bi bi-plus-circle d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="ptypeSearch" class="form-control"
                                            placeholder="Search paper type...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="ptype-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                @if(auth()->user()->level == 1)
                                                    <th>Site</th>
                                                @endif
                                                <th>Paper Type</th>
                                                <th>Paper Type Description</th>
                                                <th>Label</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ptypeTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add papertype modal -->
    <div class="modal fade" id="addPTypeModal" tabindex="-1" aria-labelledby="addPTypeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPtypeLabel">Add New Paper Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPTypeForm">
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
                            <label for="PType" class="form-label">Paper Type</label>
                            <input type="text" class="form-control" id="PType" name="PType" required>
                        </div>
                        <div class="mb-3">
                            <label for="PTypeDesc" class="form-label">Paper Type Description</label>
                            <input type="text" class="form-control" id="PTypeDesc" name="PTypeDesc" required>
                        </div>
                        <div class="mb-3">
                            <label for="DescLabel" class="form-label">Paper Type Label</label>
                            <input type="url" class="form-control" id="DescLabel" name="DescLabel" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePTypeBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit ptype modal -->
    <div class="modal fade" id="editPTypeModal" tabindex="-1" aria-labelledby="editPTypeModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <form action="{{ route('ptypes.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPTypeLabel">Edit Paper Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPTypeForm">
                            @csrf
                            <input type="hidden" id="edit_site" name="Site">
                            <input type="hidden" id="edit_ptype" name="PType">
                            <div class="mb-3">
                                <label for="update_ptype" class="form-label">Paper Type</label>
                                <input type="text" class="form-control" id="update_ptype" name="updatePType" required>
                            </div>
                            <div class="mb-3">
                                <label for="PTypeDesc" class="form-label">Paper Type Description</label>
                                <input type="text" class="form-control" id="edit_ptypedesc" name="PTypeDesc" required>
                            </div>
                            <div class="mb-3">
                                <label for="DescLabel" class="form-label">Label</label>
                                <input type="url" class="form-control" id="edit_desclabel" name="DescLabel" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updatePTypeBtn">Update Paper Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection