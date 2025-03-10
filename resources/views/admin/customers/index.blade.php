@extends('admin.layouts.customer')
@section('styles')
    <style>
        /* Enhanced styling */
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


        /* Avatar and icon styles */
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

        .fa {
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
        [class^="fa--"],
        [class*=" fa--"] {
            font-family: 'fa' !important;
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
   

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Customer Management</h3>
            <div>

                <button class="btn btn-sm btn-primary rounded-pill"
                    onclick="window.location.href='{{ route('customers.create') }}'">
                    <i class="feather feather-user-plus me-1"></i> Add Customer
                </button>
            </div>
        </div>

        @include('admin.customers.analyticsModel')

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table id="customers-table" class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4"></th>
                                <th>Customer</th>
                                <th>Policies</th>
                                <th>Mobile</th>
                                <th>Details</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $user)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-title bg-primary text-white rounded-circle">
                                                    {{ substr($user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->username ?? 'No username' }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if ($user->customer_policies_count > 0)
                                            <span class="badge bg-soft-primary text-primary rounded-pill px-2">
                                                {{ $user->customer_policies_count }}
                                            </span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary rounded-pill px-2">
                                                0
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="feather feather-smartphone me-2 text-muted"></i>
                                            {{ $user->mobile_number }}
                                        </div>
                                    </td>

                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-soft-info rounded-pill view-customer-details"
                                            data-bs-toggle="modal" data-bs-target="#customerDetailsModal"
                                            data-customer-name="{{ $user->name }}"
                                            data-customer-address="{{ $user->address }}"
                                            data-customer-city="{{ $user->city }}"
                                            data-customer-state="{{ $user->state }}"
                                            data-customer-mobile="{{ $user->mobile_number }}"
                                            data-customer-aadhar-number="{{ $user->aadhar_number ?? 'Not provided' }}"
                                            data-customer-aadhar-document="{{ $user->aadhar_document ? asset('storage/aadhar/' . $user->aadhar_document) : null }}"
                                            data-customer-pan-number="{{ $user->pan_number ?? 'Not provided' }}"
                                            data-customer-pan-document="{{ $user->pan_document ? asset('storage/pancard/' . $user->pan_document) : null }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('customers.edit', $user->id) }}"
                                                class="btn btn-sm btn-icon btn-soft-primary rounded-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Customer">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="{{ route('customers.changePassword', $user->id) }}"
                                                class="btn btn-sm btn-icon btn-soft-warning rounded-circle "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Change Password">
                                                <i class="fa-solid fa-lock"></i>
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

        <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="customerModalTitle">
                            <i class="feather feather-user me-2"></i>
                            Customer Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-transparent py-3">
                                        <h6 class="mb-0">
                                            <i class="feather feather-map-pin me-2"></i>
                                            Address Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <address class="mb-0" id="customerAddressInfo">
                                            <strong id="modalCustomerName"></strong><br>
                                            <span id="modalCustomerAddress"></span><br>
                                            <span id="modalCustomerCityState"></span><br>
                                            <i class="feather feather-phone me-1 small"></i> <span
                                                id="modalCustomerMobile"></span>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-transparent py-3">
                                        <h6 class="mb-0">
                                            <i class="feather feather-file me-2"></i>
                                            Documents
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            <div
                                                class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>
                                                    <i class="feather feather-credit-card me-2 text-primary"></i>
                                                    <strong>Aadhar Card</strong>
                                                    <p class="mb-0 small text-muted" id="modalCustomerAadharNumber"></p>
                                                </div>
                                                <div id="modalCustomerAadharDocLink">
                                                    {{-- Aadhar document link will be placed here by JS --}}
                                                </div>
                                            </div>
                                            <div
                                                class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>
                                                    <i class="feather feather-credit-card me-2 text-info"></i>
                                                    <strong>PAN Card</strong>
                                                    <p class="mb-0 small text-muted" id="modalCustomerPanNumber"></p>
                                                </div>
                                                <div id="modalCustomerPanDocLink">
                                                    {{-- PAN document link will be placed here by JS --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">
                            <i class="feather feather-edit me-1"></i> Edit Details
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $(document).ready(function() {
            $('.view-customer-details').click(function() {
                var customerName = $(this).data('customer-name');
                var customerAddress = $(this).data('customer-address');
                var customerCity = $(this).data('customer-city');
                var customerState = $(this).data('customer-state');
                var customerMobile = $(this).data('customer-mobile');
                var customerAadharNumber = $(this).data('customer-aadhar-number');
                var customerAadharDocument = $(this).data('customer-aadhar-document');
                var customerPanNumber = $(this).data('customer-pan-number');
                var customerPanDocument = $(this).data('customer-pan-document');

                $('#customerModalTitle').text(customerName + '\'s Details');
                $('#modalCustomerName').text(customerName);
                $('#modalCustomerAddress').text(customerAddress);
                $('#modalCustomerCityState').text(customerCity + ', ' + customerState);
                $('#modalCustomerMobile').text(customerMobile);
                $('#modalCustomerAadharNumber').text(customerAadharNumber);
                $('#modalCustomerPanNumber').text(customerPanNumber);

                // Handle Aadhar Document Link
                var aadharDocLinkContainer = $('#modalCustomerAadharDocLink');
                aadharDocLinkContainer.empty(); // Clear previous link
                if (customerAadharDocument) {
                    aadharDocLinkContainer.append(
                        '<a href="' + customerAadharDocument +
                        '" target="_blank" class="btn btn-sm btn-soft-primary rounded-pill">' +
                        '<i class="feather feather-download me-1"></i> View' +
                        '</a>'
                    );
                } else {
                    aadharDocLinkContainer.append(
                        '<span class="badge bg-soft-warning text-warning">Not Uploaded</span>'
                    );
                }

                // Handle PAN Document Link
                var panDocLinkContainer = $('#modalCustomerPanDocLink');
                panDocLinkContainer.empty(); // Clear previous link
                if (customerPanDocument) {
                    panDocLinkContainer.append(
                        '<a href="' + customerPanDocument +
                        '" target="_blank" class="btn btn-sm btn-soft-primary rounded-pill">' +
                        '<i class="feather feather-download me-1"></i> View' +
                        '</a>'
                    );
                } else {
                    panDocLinkContainer.append(
                        '<span class="badge bg-soft-warning text-warning">Not Uploaded</span>'
                    );
                }
            });
        });
    </script>
@endsection