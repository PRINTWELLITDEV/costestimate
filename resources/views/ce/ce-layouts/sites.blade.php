@extends('ce/ce-layouts.app')
@section('title', 'Sites | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Site Management</h1>
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
                                    <button type="button" id="btnAddSite"
                                        class="btn btn-primary d-flex align-items-center me-2" data-bs-toggle="modal"
                                        data-bs-target="#addSiteModal">
                                        <i class="bi bi-plus-circle d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add Site</span>
                                        <i class="bi bi-plus-circle d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="siteSearch" class="form-control"
                                            placeholder="Search sites...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="sites-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Site Link</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="siteTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add site modal -->
    <div class="modal fade" id="addSiteModal" tabindex="-1" aria-labelledby="addSiteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSiteLabel">Add New Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSiteForm">
                        @csrf
                        <div class="mb-3">
                            <label for="logo_pic_url" class="form-label">Site Logo</label>
                            <input type="file" class="form-control" id="logo_pic_url" name="logo_pic_url"
                                accept="image/png, image/jpeg, image/jpg, image/webp">
                        </div>
                        <div class="mb-3">
                            <label for="site" class="form-label">Site Code</label>
                            <input type="text" class="form-control" id="site" name="site" required>
                        </div>
                        <div class="mb-3">
                            <label for="site_desc" class="form-label">Site Description</label>
                            <input type="text" class="form-control" id="site_desc" name="site_desc" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="site_link" class="form-label">Site Link</label>
                            <input type="url" class="form-control" id="site_link" name="site_link" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSiteBtn">Save Site</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit site modal -->
    <div class="modal fade" id="editSiteModal" tabindex="-1" aria-labelledby="editSiteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md scrollable">
            <div class="modal-content">
                <form action="{{ route('sites.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSiteLabel">Edit Site</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editSiteForm">
                            @csrf
                            <input type="hidden" id="edit_site" name="site">
                            <div class="mb-3 text-center">
                                <img id="edit-logo-preview" src="" alt="logo preview" class="rounded-circle mb-2 border"
                                    width="100" height="100">
                            </div>
                            <div class="mb-3">
                                <label for="edit_logo_pic_url" class="form-label">Site Logo</label>
                                <input type="file" class="form-control" id="edit_logo_pic_url" name="logo_pic_url"
                                    accept="image/png, image/jpeg, image/jpg, image/webp">
                            </div>
                            <div class="mb-3">
                                <label for="edit_site_desc" class="form-label">Site Description</label>
                                <input type="text" class="form-control" id="edit_site_desc" name="site_desc" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="edit_address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_site_link" class="form-label">Site Link</label>
                                <input type="url" class="form-control" id="edit_site_link" name="site_link" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateSiteBtn">Update Site</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection