@extends('admin.layouts.customer')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Enhanced styling (from Customer Management Page) - REUSED and kept consistent */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .rounded-4 {
            border-radius: 0.75rem !important;
        }

        /* Soft background colors */
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        /* Button styles */
        .btn-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: none;
        }

        .btn-soft-primary:hover {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
        }

        .btn-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
            border: none;
        }

        .btn-soft-info:hover {
            background-color: rgba(13, 202, 240, 0.2);
            color: #0dcaf0;
        }

        .btn-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: none;
        }

        .btn-soft-warning:hover {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .btn-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: none;
        }

        .btn-soft-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Avatar and icon styles - if needed */
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            font-weight: 600;
            font-size: 14px;
        }

        .feather {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            vertical-align: middle;
        }

        /* Button icon styles */
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* More refined table styles */
        .table>:not(caption)>*>* {
            padding: 0.75rem 1rem;
        }

        /* Feather icon integration */
        [class^="feather-"],
        [class*=" feather-"] {
            font-family: 'feather' !important;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
@endsection



@section('content')
    <div class="container-fluid p-3"> {{-- Reduced padding for compactness --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Manage Customer Policies</h3>
            <div>
                <button class="btn btn-sm btn-primary rounded-pill"
                    onclick="window.location.href='{{ route('customer-policies.create') }}'">
                    <i class="feather feather-file-plus me-1"></i> Add Customer Policy
                </button>
            </div>
        </div>

        {{-- Filter Section - Ultra Compact --}}
        <div class="card border-0 shadow-sm rounded-4 mb-2">
            <div class="card-body p-2">
                <form action="{{ route('customer-policies.index') }}" method="GET" class="row g-1 align-items-center"> {{-- align-items-center --}}
                    
                    {{-- Start Date Filters --}}
                    <div class="col-6 col-md-2">
                        <select name="start_year" class="form-select form-select-sm py-1" style="font-size: 0.8rem;">
                            <option value="">Start Year</option> {{-- Label moved here --}}
                            @foreach(range(date('Y'), date('Y') - 10) as $year)
                                <option value="{{ $year }}" {{ request('start_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="start_month" class="form-select form-select-sm py-1" style="font-size: 0.8rem;">
                            <option value="">Start Month</option>
                            @for($m=1; $m<=12; ++$m)
                                <option value="{{ $m }}" {{ request('start_month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Due Date (End Date) Filters --}}
                    <div class="col-6 col-md-2">
                        <select name="due_year" class="form-select form-select-sm py-1" style="font-size: 0.8rem;">
                            <option value="">Due Year</option>
                            @foreach(range(date('Y') + 5, date('Y') - 5) as $year)
                                <option value="{{ $year }}" {{ request('due_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="due_month" class="form-select form-select-sm py-1" style="font-size: 0.8rem;">
                            <option value="">Due Month</option>
                            @for($m=1; $m<=12; ++$m)
                                <option value="{{ $m }}" {{ request('due_month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                         <div class="d-grid gap-1 d-flex justify-content-end"> {{-- Align right --}}
                            <button type="submit" class="btn btn-sm btn-soft-primary px-3 py-1" style="font-size: 0.8rem;">Filter</button>
                            <a href="{{ route('customer-policies.index') }}" class="btn btn-sm btn-soft-secondary px-3 py-1" style="font-size: 0.8rem;">Reset</a>
                         </div>
                    </div>
                </form>
            </div>
        </div>

        @include('admin.customers_policies.analyticsModel')

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body px-0 pb-0 pt-0"> {{-- Reduced top/bottom padding --}}
                <div class="table-responsive">
                    {{-- Added table-sm for compact layout --}}
                    <table id="customers-table" class="table table-hover table-sm align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4"></th>
                                <th>Customer</th>
                                <th>Policy No.</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th> {{-- Merged Header --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerPolicies as $key => $policy)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $key + 1 }}</td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title bg-primary text-white rounded-circle" style="width: 30px; height: 30px; font-size: 12px;">
                                                    {{ substr($policy->user_name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium" style="font-size: 0.9rem;">{{ Str::limit($policy->user_name, 16, '..') }}</h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($policy->policy_holder_name ?? 'No Name', 16, '..') }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if (!empty($policy->policy_link))
                                            <a href="{{ $policy->policy_link }}" target="_blank" class="ms-2">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        @endif
                                        {{ $policy->policy_no }}

                                    </td>
                                    <td>{{ $policy->policy_start_date }}</td>
                                    <td>{{ $policy->policy_end_date }}</td>


                                    <td>
                                        @if ($policy->status == 'active')
                                            <span class="badge bg-soft-success text-success rounded-pill px-2">{{ ucfirst($policy->status) }}</span>
                                        @elseif ($policy->status == 'expired')
                                            <span class="badge bg-soft-danger text-danger rounded-pill px-2">{{ ucfirst($policy->status) }}</span>
                                        @elseif ($policy->status == 'cancelled')
                                            <span class="badge bg-soft-warning text-warning rounded-pill px-2">{{ ucfirst($policy->status) }}</span>
                                        @elseif ($policy->status == 'pending')
                                            <span class="badge bg-soft-secondary text-secondary rounded-pill px-2">{{ ucfirst($policy->status) }}</span>
                                        @elseif ($policy->status == 'approved')
                                            <span class="badge bg-soft-primary text-primary rounded-pill px-2">{{ ucfirst($policy->status) }}</span>
                                        @else
                                            {{ $policy->status }}
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-3"> {{-- Increased gap slightly for touch targets --}}
                                            <a href="javascript:void(0);" 
                                                class="text-info view-policy-details" 
                                                data-bs-toggle="modal" data-bs-target="#policyDetailsModal"
                                                data-policy-status="{{ $policy->status }}"
                                                data-policy-net-amount="{{ $policy->net_amount }}"
                                                data-policy-gst="{{ $policy->gst }}"
                                                data-policy-premium="{{ $policy->premium }}"
                                                data-policy-insurance-company="{{ $policy->insurance_company }}"
                                                data-policy-type="{{ $policy->policy_type }}"
                                                data-policy-product="{{ $policy->product_name }}"
                                                title="View Details">
                                                <i class="fa-solid fa-eye" style="font-size: 1.1rem;"></i>
                                            </a>

                                            <a href="{{ route('customer-policies.edit', $policy->id) }}"
                                                class="text-primary"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Policy">
                                                <i class="fa-solid fa-pen-to-square" style="font-size: 1.1rem;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="policyDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Larger modal for potentially more details --}}
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="policyModalTitle">
                            <i class="feather feather-file-text me-2"></i>
                            Policy Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4"> {{-- Using g-4 for more spacing like customer modal --}}
                            <div class="col-md-6"> {{-- Left side card --}}
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-transparent py-3">
                                        <h6 class="mb-0">
                                            <i class="feather feather-info me-2"></i>
                                            Policy Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Status:</strong>
                                                <span id="modalPolicyStatus"></span>
                                            </li>
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Type:</strong> {{-- Policy Type moved to modal --}}
                                                <span id="modalPolicyType"></span>
                                            </li>
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Product:</strong> {{-- Policy Product moved to modal --}}
                                                <span id="modalPolicyProduct"></span>
                                            </li>
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Insurance Company:</strong>
                                                <span id="modalPolicyInsuranceCompany"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> {{-- Right side card --}}
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-transparent py-3">
                                        <h6 class="mb-0">
                                            <i class="feather feather-dollar-sign me-2"></i>
                                            Financial Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Net Amount:</strong>
                                                <span id="modalPolicyNetAmount"></span>
                                            </li>
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>GST:</strong>
                                                <span id="modalPolicyGST"></span>
                                            </li>
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <strong>Premium:</strong>
                                                <span id="modalPolicyPremium"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">
                            <i class="feather feather-edit me-1"></i> Edit Details {{-- Kept "Edit Details" for consistency, adjust route if needed --}}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.view-policy-details').click(function() {
                var policyStatus = $(this).data('policy-status');
                var policyNetAmount = $(this).data('policy-net-amount');
                var policyGST = $(this).data('policy-gst');
                var policyPremium = $(this).data('policy-premium');
                var policyInsuranceCompany = $(this).data('policy-insurance-company');
                var policyType = $(this).data('policy-type'); // Get Policy Type
                var policyProduct = $(this).data('policy-product'); // Get Policy Product

                $('#modalPolicyStatus').text(policyStatus);
                $('#modalPolicyNetAmount').text(policyNetAmount);
                $('#modalPolicyGST').text(policyGST);
                $('#modalPolicyPremium').text(policyPremium);
                $('#modalPolicyInsuranceCompany').text(policyInsuranceCompany);
                $('#modalPolicyType').text(policyType); // Set Policy Type in Modal
                $('#modalPolicyProduct').text(policyProduct); // Set Policy Product in Modal
            });
        });
    </script>
@endsection