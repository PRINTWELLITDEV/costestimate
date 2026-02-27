@forelse($sites as $site)
    @php
        $logo_pic_url = $site->logo_pic_url ?? 'uploads/user-profile/noprofile.png';
        if (!file_exists(public_path($logo_pic_url)) || !$logo_pic_url) {
            $logo_pic_url = 'uploads/user-profile/noprofile.png';
        }
    @endphp

    <tr 
        data-site="{{ $site->site }}"
        data-site_desc="{{ $site->site_desc }}"
        data-address="{{ $site->address }}"
        data-site_link="{{ $site->site_link }}"
        data-logo="{{ asset($logo_pic_url) }}"
    >
        <td>
            <div class="d-flex gap-2">
                <div class="profile-text align-items-center">
                    <div class="fw-bold">
                        {{ $site->site_desc ?? 'N/A' }}
                    </div>
                    <div class="small">
                        {{ 'Site Code: ' . $site->site }}
                    </div>
                </div>

                <img src="{{ asset($logo_pic_url) }}" alt="profile" 
                    class="profile-img rounded-circle border border-3 me-2">
            </div>
        </td>
        <td>{{ $site->address ?? 'N/A' }}</td>
        <td>{{ $site->site_link }}</td>
        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="#" class="dropdown-item edit-site-btn" data-siteid="{{ $site->site }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-site-btn" 
                            data-siteid="{{ $site->site }}"
                            data-site_desc="{{ $site->site_desc }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse