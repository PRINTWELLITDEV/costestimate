@extends('ce/ce-layouts.app')
@section('title', 'Users | ' . config('app.name'))
@section('content')

    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row d-flex align-items-center justify-content-between">
                    <div class="col">
                        <h1 class="d-inline-block mb-0 me-3">User Management</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content-body">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
                        <div class="stats-card primary">
                            <div class="stats-label">Total Users</div>
                            <div class="stats-number" id="total-users">{{ $totalUsers }}</div>
                            <i class="fas fa-users stats-icon text-primary"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
                        <div class="stats-card success">
                            <div class="stats-label">Status</div>
                            <div class="stats-number" id="total-status">{{ $totalStatusActive }}</div>
                            <i class="fas fa-user-check stats-icon text-success"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
                        <div class="stats-card danger">
                            <div class="stats-label">Admin</div>
                            <div class="stats-number" id="total-admins">{{ $totalLevel1 }}</div>
                            <i class="fas fa-shield stats-icon text-danger"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card p-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" id="btnAddUser"
                                        class="btn btn-primary d-flex align-items-center me-2" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                        <i class="bi bi-person-plus-fill d-none d-sm-inline me-2"></i>
                                        <span class="d-none d-sm-inline">Add User</span>
                                        <i class="bi bi-person-plus-fill d-inline d-sm-none"></i>
                                    </button>

                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" id="userSearch" class="form-control"
                                            placeholder="Search users...">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    </div>
                                </div>

                                <div class="table-responsive table-view">
                                    <table id="users-table" class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th width="25%">Site</th>
                                                <th width="10%">Role</th>
                                                <th width="10%">Status</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTableBody"></tbody>
                                    </table>
                                </div>

                            </div> <!-- /.card-body -->
                        </div> <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add User Modal (update the profile picture input section) -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-l">
            <div class="modal-content">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 text-center" id="add-user-profile-preview-container">
                                <img id="adduser-profile-preview" src="{{ asset('uploads/user-profile/noprofile.png') }}"
                                    alt="profile preview" class="rounded-circle mb-2 border" width="150" height="150">
                            </div>
                            <div class="mb-3 align-bottom">
                                <label for="add_profile_pic_url" class="form-label">Profile Picture</label>
                                <input type="file" name="profile_pic_url" id="add_profile_pic_url" class="form-control"
                                    accept="image/png, image/jpeg, image/jpg, image/webp">
                                @error('profile_pic_url') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                @if(auth()->user()->level == 1)
                                    <label for="site" class="form-label">Site</label>
                                    <div class="input-group">
                                        <select name="site" id="site" class="form-select" required>
                                            <option disabled selected>Select Site</option>
                                            @foreach($sites as $site)
                                                <option value="{{ $site->site }}" {{ old('site') == $site->site ? 'selected' : '' }}>
                                                    {{ $site->site_desc }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('site') <div class="text-danger small">{{ $message }}</div> @enderror
                                @else
                                    <input type="hidden" name="site" value="{{ auth()->user()->site }}">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="userid" class="form-label">User ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="userid" name="userid"
                                        value="{{ old('userid') }}" required maxlength="8" placeholder="User ID"
                                        autocomplete="off">
                                </div>
                                @error('userid') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                        maxlength="255" placeholder="Name" autocomplete="off">
                                </div>
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" maxlength="255" required placeholder="Email"
                                        autocomplete="off">
                                </div>
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required
                                        maxlength="255" placeholder="Password" autocomplete="off">
                                </div>
                                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('users.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit-userid" name="edit_userid">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editUserLabel">Edit User</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-4 text-center mb-3 mb-md-0 align-self-center">
                                <div class="mb-3 text-center" id="edit-user-profile-preview-container">
                                    <img id="edit-user-profile-preview"
                                        src="{{ asset('uploads/user-profile/noprofile.png') }}" alt="profile preview"
                                        class="rounded-circle mb-2 border" width="100" height="100">
                                </div>
                                <div class="mb-3 align-bottom">
                                    <label for="edit_profile_pic_url" class="form-label">Profile Picture</label>
                                    <input type="file" name="edit_profile_pic_url" id="edit_profile_pic_url"
                                        class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="mb-3">
                                    @if(auth()->user()->level == 1)
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-building"></i>
                                            </span>
                                            <input type="text" class="form-control" id="edit_site" name="site"
                                                readonly maxlength="8" placeholder="Site">
                                        </div>
                                    @else
                                        <input type="hidden" name="site" id="edit_site">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        <input type="text" class="form-control" id="edit_userid_display" name="userid"
                                            readonly maxlength="8" placeholder="User ID">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" class="form-control" id="edit_name" name="name" maxlength="255"
                                            placeholder="Name">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control" id="edit_email" name="email"
                                            maxlength="255" required placeholder="Email">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="edit_password" name="password"
                                            maxlength="255" placeholder="Password (leave blank to keep current)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text py-2">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <select name="level" id="edit_level" class="form-select" placeholder="Level">
                                        <option value="" disabled selected>Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->level }}">
                                                {{ $level->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text py-2">
                                        <i class="bi bi-person-check"></i>
                                    </span>
                                    <select name="status" id="edit_status" class="form-select" placeholder="Status">
                                        <option value="" disabled selected>Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text py-2">
                                        <i class="bi bi-gender-ambiguous"></i>
                                    </span>
                                    <select name="gender" id="edit_gender" class="form-select" placeholder="Gender">
                                        <option value="" disabled selected>Select a gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" class="form-control" id="edit_department" name="department"
                                        maxlength="50" placeholder="Department">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-diagram-3"></i>
                                    </span>
                                    <input type="text" class="form-control" id="edit_section" name="section" maxlength="50"
                                        placeholder="Section">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-workspace"></i>
                                    </span>
                                    <input type="text" class="form-control" id="edit_position" name="position"
                                        maxlength="50" placeholder="Position">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnUpdateUser">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection