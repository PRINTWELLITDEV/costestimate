@forelse($pricings as $pricing)
    <tr data-id="{{ $pricing->id }}" data-site="{{ $pricing->Site }}" data-vendorgroup="{{ $pricing->VendorGroup }}"
        data-ptype="{{ $pricing->PType }}" data-vendor="{{ $pricing->Vendor }}" data-stockcode="{{ $pricing->StockCode }}"
        data-um="{{ $pricing->UM }}"
        data-effectivedate="{{ date('m/d/Y', strtotime($pricing->EffectiveDate)) }}" data-currcode="{{ $pricing->Currcode }}" data-price_mt="{{ number_format($pricing->Price_MT, 2) }}"
        data-price_sheet="{{ number_format($pricing->Price_Sheet, 2) }}" data-price_pound="{{ number_format($pricing->Price_Pound, 2) }}" data-price_bale="{{ number_format($pricing->Price_Bale, 2) }}">

        @if(auth()->user()->level == 1)
            <td>{{ $pricing->Site }}</td>
        @endif
        <td>{{ $pricing->VendorGroup ?? 'N/A' }}</td>
        <td>
            <div class="d-flex gap-2">
                <div class="align-items-center">
                    <div class="fw-bold">
                        {{ $pricing->PType ?? 'N/A' }}
                    </div>
                    <div class="small">
                        {{ $pricing->PTypeDesc ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex gap-2">
                <div class="align-items-center">
                    <div class="fw-bold">
                        {{ $pricing->Vendor ?? 'N/A' }}
                    </div>
                    <div class="small">
                        {{ $pricing->VendName ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </td>

        <td>
            <div class="d-flex gap-2">
                <div class="align-items-center">
                    <div class="fw-bold">
                        {{ $pricing->StockCode ?? 'N/A' }}
                    </div>
                    <div class="small">
                        {{ $pricing->StockDesc ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </td>
        <td>{{ $pricing->UM ?? '' }}</td>
        <td>{{ date('m/d/Y', strtotime($pricing->EffectiveDate)) }}</td>
        <td>{{ $pricing->Currcode }}</td>
        <td class="text-end pe-4">{{ number_format($pricing->Price_MT, 2) }}</td>
        <td class="text-end pe-4">{{ number_format($pricing->Price_Sheet, 2) }}</td>
        <td class="text-end pe-4">{{ number_format($pricing->Price_Pound, 2) }}</td>
        <td class="text-end pe-4">{{ number_format($pricing->Price_Bale, 2) }}</td>
        <td class="align-middle text-center">
            <div class="dropdown table-action-menu">
                <a href="#" class="nav-link dropdown-toggle" id="actionDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="">
                    <li>
                        <a href="#" class="dropdown-item edit-pricing-btn" data-site="{{ $pricing->Site }}" data-id="{{ $pricing->id }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item delete-pricing-btn" data-site="{{ $pricing->Site }}" data-id="{{ $pricing->id }}"
                            data-ptype="{{ $pricing->PType }}" data-vendor="{{ $pricing->Vendor }}" data-vendorgroup="{{ $pricing->VendorGroup }}"
                            data-stockcode="{{ $pricing->StockCode }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
@endforelse