@extends('admin.layouts.customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#"> Agent</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fa-solid fa-gear"></i> Codes Management</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <!-- Commission Filter Card -->
            @include('admin.agent.CommissionFilter')

            <!-- Commission Listing Card -->
            <div class="card shadow-sm rounded-lg">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fa-solid fa-list-ol me-2"></i> Agent Codes</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('commission.management', ['sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}"
                            class="btn btn-light btn-sm me-2">
                            <i class="fa-solid fa-sort-alpha-{{ request('sort') === 'asc' ? 'down' : 'up' }}"></i>
                        </a>
                        <button class="btn btn-danger btn-sm me-2" id="deleteSelectedBtn" disabled>
                            <i class="fa-solid fa-trash-can me-1"></i> Delete Selected
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#commissionModal">
                            <i class="fa-solid fa-plus me-1"></i> Add Agent Code
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($agentsWithCommissions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa-solid fa-circle-info me-2"></i> No Agent Code records found.
                        </div>
                    @endif

                    @foreach ($agentsWithCommissions as $agent)
                        @if (!empty($agent->agentCodes) && $agent->agentCodes->count() > 0)

                        @php
                        $softColors = [
                            '#f0f9ff', // Soft Blue
                            '#fff7ed', // Soft Orange
                            '#fefce8', // Soft Yellow
                            '#f0fdf4', // Soft Green
                            '#fdf2f8', // Soft Pink
                            '#ede9fe', // Soft Purple
                            '#ecfdf5', // Mint
                        ];
                        $randomColor = $softColors[array_rand($softColors)];
                    @endphp

                            <div class="commission-group mb-4">
                                <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary p-2 rounded-circle me-1">
                                            <i class="fa-solid fa-user-tie fs-3 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $agent->name }}</h6>
                                            <span class="text-muted small">{{ $agent->mobile_number ?? 'No phone' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light" >
                                            <tr>
                                                <th style="width: 40px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input group-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th style="width: 150px;">Code</th>
                                                <!-- Combined product and company columns -->
                                                <th style="width: 220px;">Product & Company</th>
                                                <!-- Combined financial details -->
                                                <th style="width: 220px;">Financial Details</th>
                                                <th style="width: 160px;">Payment Settings</th>
                                                <th style="width: 100px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            @foreach ($agent->agentCodes as $commission)
                                                <tr style="background-color: {{ $randomColor }}; border-radius: 0.5rem; padding: 10px;">
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input commission-checkbox"
                                                                type="checkbox" value="{{ $commission->id }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-info me-2">{{ $commission->code }}</span>
                                                            <button
                                                                class="btn btn-sm btn-icon btn-outline-secondary copy-code"
                                                                data-code="{{ $commission->code }}">
                                                                <i class="fa-regular fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <!-- Combined product & company column -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <div class="mb-1">
                                                                <i class="fa-solid fa-box text-primary me-1"></i>
                                                                <span class="fw-medium">{{ $commission->insuranceProduct->name ?? '' }}</span>
                                                            </div>
                                                            <div>
                                                                <i class="fa-solid fa-building text-secondary me-1"></i>
                                                                <span class="text-muted">{{ $commission->insuranceCompany->name ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <!-- Combined financial details -->
                                                    <td>
                                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                                            <span class="badge {{ $commission->commission_type === 'fixed' ? 'bg-success' : 'bg-warning' }} me-1">
                                                                <i class="fa-solid fa-hand-holding-dollar me-1"></i>
                                                                Comm: {{ $commission->commission }}{{ $commission->commission_type === 'fixed' ? '₹' : '%' }}
                                                            </span>
                                                            
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fa-solid fa-receipt me-1"></i>
                                                                GST: {{ $commission->gst }}%
                                                            </span>
                                                            
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fa-solid fa-tag me-1"></i>
                                                                Disc: {{ $commission->discount ?? 0 }}%
                                                            </span>
                                                            
                                                            <span class="badge bg-dark">
                                                                <i class="fa-solid fa-wallet me-1"></i>
                                                                Payout: {{ $commission->payout ?? 0 }}%
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <!-- Payment settings column -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            @php
                                                                $paymentTypeColors = [
                                                                    'agent_full_payment' => 'success',
                                                                    'commission_deducted' => 'warning',
                                                                    'pay_later_with_adjustment' => 'info',
                                                                    'pay_later' => 'secondary',
                                                                ];

                                                                $paymentTypeIcons = [
                                                                    'agent_full_payment' => 'fa-solid fa-money-bill-wave',
                                                                    'commission_deducted' => 'fa-solid fa-hand-holding-dollar',
                                                                    'pay_later_with_adjustment' => 'fa-solid fa-calendar-days',
                                                                    'pay_later' => 'fa-solid fa-clock',
                                                                ];
                                                            @endphp
                                                            
                                                            <!-- Payment Type -->
                                                            <span class="badge bg-{{ $paymentTypeColors[$commission->payment_type] ?? 'secondary' }} mb-1">
                                                                <i class="{{ $paymentTypeIcons[$commission->payment_type] ?? 'fa-solid fa-money-bill' }} me-1"></i>
                                                                {{ ucwords(str_replace('_', ' ', $commission->payment_type)) }}
                                                            </span>
                                                            
                                                            <!-- Commission Settlement Status - only show if settled -->
                                                            @if($commission->commission_settlement)
                                                                <span class="badge  text-muted

">
                                                                    <i class="fa-solid fa-circle-check me-1 text-success"></i> Commission Settlement
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-outline-primary me-1 edit-commission"
                                                                data-id="{{ $commission->id }}"
                                                                data-agent="{{ $commission->user_id }}"
                                                                data-product="{{ $commission->insurance_product_id }}"
                                                                data-company="{{ $commission->insurance_company_id }}"
                                                                data-comm-type="{{ $commission->commission_type }}"
                                                                data-comm-value="{{ $commission->commission }}"
                                                                data-payment="{{ $commission->payment_type }}"
                                                                data-gst="{{ $commission->gst }}"
                                                                data-discount="{{ $commission->discount ?? 0 }}"
                                                                data-payout="{{ $commission->payout ?? 0 }}"
                                                                {{-- data-settlement="{{ $commission->commission_settlement }}" --}}
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-outline-danger delete-commission"
                                                                data-id="{{ $commission->id }}" data-bs-toggle="tooltip"
                                                                title="Delete">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="d-flex justify-content-center mt-4">
                        {{ $agentsWithCommissions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Modal -->
    @include('admin.agent.CommissionModal')

    <!-- Delete Confirmation Modal -->
    @include('admin.agent.DeleteConfirmationModal')

    <!-- Bulk Delete Modal -->
    @include('admin.agent.BulkDeleteModal')
@endsection

@push('scripts')
    @include('admin.agent.script')
@endpush

