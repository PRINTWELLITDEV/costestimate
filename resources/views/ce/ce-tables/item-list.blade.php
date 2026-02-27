@forelse($items as $item)
    <tr data-site="{{ $item->Site }}" data-prodgroup="{{ $item->ProdGroup }}" data-ptype="{{ $item->PType }}"
        data-ItemCode="{{ $item->ItemCode }}" data-ItemDesc="{{ $item->ItemDesc }}" data-UM="{{ $item->UM }}"
        data-GSM="{{ $item->GSM }}" data-Caliper="{{ $item->Caliper }}" data-PPR="{{ $item->PPR }}"
        data-Cbnum="{{ $item->Cbnum }}" data-Width="{{ $item->Width }}" data-Length="{{ $item->Length }}">

        @if(auth()->user()->level == 1)
            <td>{{ $item->Site }}</td>
        @endif
        <td>{{ $item->ProdGroup }}</td>
        <td>{{ $item->PType }}</td>
        <td>{{ $item->UM }}</td>
        <td class="text-end pe-4">{{ $item->GSM ? $item->GSM : '0' }}</td>
        <td class="text-end pe-4">{{ $item->Caliper ? $item->Caliper : '0' }}</td>
        <td class="text-end pe-4">{{ $item->PPR ? $item->PPR : '0' }}</td>
        <td class="text-end pe-4">{{ $item->Cbnum ? $item->Cbnum : '0' }}</td>
        <td class="text-end pe-4">{{ $item->Width == 0 ? '0' : number_format($item->Width) }}</td>
        <td class="text-end pe-4">{{ $item->Length == 0 ? '0' : number_format($item->Length) }}</td>
        <td class="fw-bold">{{ $item->ItemCode }}</td>
        <td>{{ $item->ItemDesc }}</td>

        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="{{ route('items.edit', ['site' => $item->Site, 'item_code' => $item->ItemCode]) }}" class="dropdown-item edit-item-btn" data-site="{{ $item->Site }}"
                            data-itemcode="{{ $item->ItemCode }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-item-btn" data-site="{{ $item->Site }}"
                            data-itemcode="{{ $item->ItemCode }}" data-itemdesc="{{ $item->ItemDesc }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse