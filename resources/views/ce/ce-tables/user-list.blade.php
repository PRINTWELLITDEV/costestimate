@forelse($users as $user)
    @php
        $profile_pic_url = $user->profile_pic_url ?? 'uploads/user-profile/noprofile.png';
        if (!file_exists(public_path($profile_pic_url)) || !$profile_pic_url) {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        }
    @endphp

    <tr data-userid="{{ $user->userid }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-site="{{ $user->site }}" data-site_desc="{{ $user->site_desc }}" 
        data-level="{{ $user->level }}" data-role="{{ $user->role }}" data-status="{{ $user->status }}" 
        data-department="{{ $user->department }}" data-position="{{ $user->position }}" data-section="{{ $user->section }}" 
        data-gender="{{ $user->gender }}" data-profile="{{ asset($profile_pic_url) }}" data-create_date="{{ date('d F Y', strtotime($user->create_date)) }}"
        >

        <td class="align-middle">
            <div class="d-flex gap-2">
                <div class="profile-text align-items-center">
                    <div class="fw-bold">
                        {{ $user->name }}
                    </div>
                    <div class="small">
                        {{ 'User ID: ' . $user->userid }}
                    </div>
                </div>

                <img src="{{ asset($profile_pic_url) }}" alt="profile" 
                    class="profile-img rounded-circle border border-3 me-2">
            </div>
        </td>
        <td class="align-middle"> {{ $user->site_desc ?? 'N/A' }} </td>
        <td class="align-middle">
            @if($user->level == 1)
                <span class="badge bg-danger">{{ $user->role }}</span>
            @elseif($user->level == 2)
                <span class="badge bg-primary">{{ $user->role }}</span>
            @else
                <span class="badge bg-secondary">{{ $user->role }}</span>
            @endif
        </td>
        <td class="align-middle">
            @if($user->status == 1)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-secondary">Inactive</span>
            @endif
        </td>
        <td class="align-middle text-center">
            <!-- <button class="btn btn-sm btn-primary" data-userid="{{ $user->userid }}">
                <i class="bi bi-three-dots"></i>
            </button> -->
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="{{ route('ce.userprofile', $user->userid) }}" class="dropdown-item">
                            <i class="bi bi-eye"></i> View Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item edit-user-btn" data-userid="{{ $user->userid }}">
                            <i class="bi bi-pencil-square"></i> Edit User
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-user-btn" 
                            data-userid="{{ $user->userid }}"
                            data-name="{{ $user->name }}">
                            <i class="bi bi-trash"></i> Delete User
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse