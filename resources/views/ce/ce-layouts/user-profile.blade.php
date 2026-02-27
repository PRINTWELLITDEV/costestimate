@extends('ce/ce-layouts.app')

@section('title', 'Profile | '.config('app.name'))

@section('content')
    <div class="app-content-wrapper">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col mb-3 d-flex align-items-center">
                        <h1 class="d-inline-block mb-0 me-3">Profile</h1>
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
                <!-- Profile Header Card -->
                <div class="card profile-header-card mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="position-relative">
                                    <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}" 
                                         alt="Profile" class="profile-avatar" 
                                         data-bs-toggle="modal" data-bs-target="#profilePicModal">
                                    <div class="avatar-status {{ $onlineUser ? 'bg-success' : 'bg-secondary' }}"></div>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="profile-name mb-1">{{ $user->name ?? $user->userid }}</h2>
                                <p class="profile-title mb-2">{{ $user->department ?? 'Department' }} - {{ $user->position ?? 'Employee' }}</p>
                                <div class="profile-meta">
                                    <span class="badge bg-primary me-2">
                                        <i class="bi bi-building me-1"></i>{{ $siteDesc ?? $user->rssite }}
                                    </span>
                                    <span class="badge bg-success me-2">
                                        <i class="bi bi-shield-check me-1"></i>Level {{ $user->level }} - {{ $levelrole }}
                                    </span>
                                    <span class="badge bg-info">
                                        <i class="bi bi-calendar3 me-1"></i>Since {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row g-4">
                    <!-- Left Column - Profile Info -->
                    <div class="col-12 col-lg-8">
                        <!-- About Section -->
                        <div class="card profile-card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-person-lines-fill me-2 text-primary"></i>About
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">Full Name</label>
                                            <p class="info-value">{{ $user->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">User ID</label>
                                            <p class="info-value">{{ $user->userid }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">Email</label>
                                            <p class="info-value">{{ $user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">Gender</label>
                                            <p class="info-value">{{ $user->gender ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">Department</label>
                                            <p class="info-value">{{ $user->department ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- <div class="info-item">
                                            <label class="info-label">Section</label>
                                            <p class="info-value">{{ $user->section ?? '-' }}</p>
                                        </div> -->
                                        <div class="info-item">
                                            <label class="info-label">Position</label>
                                            <p class="info-value">{{ $user->position ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="card profile-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-building me-2 text-primary"></i>Company Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <label class="info-label">Company</label>
                                            <p class="info-value">{{ $siteDesc ?? $user->rssite }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-item">
                                            <label class="info-label">Address</label>
                                            <p class="info-value">{{ $siteAddress ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Actions -->
                    <div class="col-12 col-lg-4">
                        @if(Auth::user() == $user || Auth::user()->userid == 'sa')
                        <!-- Quick Actions -->
                        <div class="card profile-card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="bi bi-person-gear me-2"></i>Edit Personal Info
                                    </button>
                                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-shield-lock me-2"></i>Change Password
                                    </button>
                                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#accountSettingsModal">
                                        <i class="bi bi-gear me-2"></i>Account Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Account Stats -->
                        <div class="card profile-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-graph-up me-2 text-primary"></i>Account Stats
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="stat-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Access Level</span>
                                        <span class="badge {{ $user->level>=1 ? 'bg-success' : 'bg-danger' }}">Level {{ $user->level }}</span>
                                    </div>
                                </div>
                                <div class="stat-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Member Since</span>
                                        <span class="stat-value">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="stat-label">Status</span>
                                        <span class="badge {{ $onlineUser ? 'bg-success' : 'bg-secondary' }}">{{ $onlineUser ? 'Online' : 'Offline' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modals -->
@if(Auth::user() == $user || Auth::user()->userid == 'sa')
<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">
                    <i class="bi bi-person-gear me-2"></i>Edit Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user-profile.update', $user->userid) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="existing_profile_pic_url" value="{{ $user->profile_pic_url }}">
                    
                    <!-- Profile Picture Section -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="edit-user-profile-preview"
                                 src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : asset('uploads/user-profile/noprofile.png') }}"
                                 alt="Profile Preview"
                                 class="rounded-circle border shadow-sm"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <label for="edit_profile_pic" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer" style="transform: translate(25%, 25%);">
                                <i class="bi bi-camera"></i>
                            </label>
                        </div>
                        <input type="file" id="edit_profile_pic" name="profile_pic_url" class="d-none" accept="image/*">
                        <p class="text-muted small mt-2">Click the camera icon to change your profile picture</p>
                    </div>

                    <!-- Form Fields -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" value="{{ old('department', $user->department) }}" placeholder="Enter department">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Section</label>
                            <input type="text" class="form-control" name="section" value="{{ old('section', $user->section) }}" placeholder="Enter section">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" value="{{ old('position', $user->position) }}" placeholder="Enter position">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" method="POST" action="{{ route('user-profile.change-password', $user->userid) }}">
                @csrf
                <div class="modal-body">
                    <div id="changePasswordMsg"></div>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-shield-check me-1"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Account Settings Modal -->
<div class="modal fade" id="accountSettingsModal" tabindex="-1" aria-labelledby="accountSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountSettingsModalLabel">
                    <i class="bi bi-gear me-2"></i>Account Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">User ID</label>
                    <input type="text" class="form-control" value="{{ $user->userid }}" disabled>
                    <small class="text-muted">User ID cannot be changed</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    <small class="text-muted">Contact administrator to change email</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Profile Picture Modal -->
<div class="modal fade" id="profilePicModal" tabindex="-1" aria-labelledby="profilePicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center p-2">
                <img src="{{ $user->profile_pic_url }}" alt="Profile Picture" class="img-fluid rounded shadow-lg" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>
@endsection
