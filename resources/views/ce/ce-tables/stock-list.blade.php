@forelse($stocks as $stock)
    <tr data-site="{{ $stock->Site }}" data-prodgroup="{{ $stock->ProdGroup }}" data-ptype="{{ $stock->PType }}"
        data-StockCode="{{ $stock->StockCode }}" data-StockDesc="{{ $stock->StockDesc }}"
        data-GSM="{{ $stock->GSM }}" data-Caliper="{{ $stock->Caliper }}" data-PPR="{{ $stock->PPR }}"
        data-Cbnum="{{ $stock->Cbnum }}" data-Width="{{ $stock->Width }}" data-Length="{{ $stock->Length }}">

        @if(auth()->user()->level == 1)
            <td>{{ $stock->Site }}</td>
        @endif
        <td>{{ $stock->ProdGroup }}</td>
        <td>{{ $stock->PType }}</td>
        <td class="text-end pe-4">{{ $stock->GSM ? $stock->GSM : '0' }}</td>
        <td class="text-end pe-4">{{ $stock->Caliper ? $stock->Caliper : '0' }}</td>
        <td class="text-end pe-4">{{ $stock->PPR ? $stock->PPR : '0' }}</td>
        <td class="text-end pe-4">{{ $stock->Cbnum ? $stock->Cbnum : '0' }}</td>
        <td class="text-end pe-4">{{ $stock->Width == 0 ? '0' : number_format($stock->Width) }}</td>
        <td class="text-end pe-4">{{ $stock->Length == 0 ? '0' : number_format($stock->Length) }}</td>
        <td class="fw-bold">{{ $stock->StockCode }}</td>
        <td>{{ $stock->StockDesc }}</td>

        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="{{ route('stocks.edit', ['site' => $stock->Site, 'stock_code' => $stock->StockCode]) }}" class="dropdown-item edit-stock-btn" data-site="{{ $stock->Site }}"
                            data-stockcode="{{ $stock->StockCode }}" data-stockdesc="{{ $stock->StockDesc }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-stock-btn" data-site="{{ $stock->Site }}"
                            data-stockcode="{{ $stock->StockCode }}" data-stockdesc="{{ $stock->StockDesc }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse