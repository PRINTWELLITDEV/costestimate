@forelse($vendors as $vendor)
    <tr data-site="{{ $vendor->Site }}" 
        data-group="{{ $vendor->Group }}"
        data-vendnum="{{ $vendor->Vendnum }}" 
        data-name="{{ $vendor->Name }}"
        data-currcode="{{ $vendor->Currcode }}">

        @if(auth()->user()->level == 1)
            <td>{{ $vendor->Site }}</td>
        @endif
        <td>{{ $vendor->Group }}</td>
        <td>{{ $vendor->Vendnum }}</td>
        <td>{{ $vendor->Name }}</td>
        <td>{{ $vendor->Currcode }}</td>
        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="#" class="dropdown-item edit-vendor-btn" data-site="{{ $vendor->Site }}"
                            data-vendnum="{{ $vendor->Vendnum }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-vendor-btn" data-site="{{ $vendor->Site }}"
                            data-vendnum="{{ $vendor->Vendnum }}" data-name="{{ $vendor->Name }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse