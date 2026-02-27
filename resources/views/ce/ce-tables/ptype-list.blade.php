@forelse($ptypes as $ptype)

    <tr data-site="{{ $ptype->Site }}" data-ptype="{{ $ptype->PType }}" data-ptypedesc="{{ $ptype->PTypeDesc }}"
        data-desclabel="{{ $ptype->DescLabel }}">
        @if(auth()->user()->level == 1)
            <td>{{ $ptype->Site }}</td>
        @endif
        <td>{{ $ptype->PType }}</td>
        <td>{{ $ptype->PTypeDesc }}</td>
        <td>{{ $ptype->DescLabel }}</td>
        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="#" class="dropdown-item edit-ptype-btn" 
                            data-site="{{ $ptype->Site }}"
                            data-pType="{{ $ptype->PType }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-ptype-btn" 
                            data-site="{{ $ptype->Site }}"
                            data-ptype="{{ $ptype->PType }}" 
                            data-ptypedesc="{{ $ptype->PTypeDesc }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse