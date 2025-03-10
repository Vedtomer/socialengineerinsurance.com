@extends('admin.layouts.customer')


@section('content')
    <?php 
    // Access analytics data using helper function
    $analytics = getCustomerAnalytics();
    ?>

    <div class="container-fluid p-4">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Customer Management</h3>
            <div>
               
                <button class="btn btn-sm btn-primary rounded-pill" onclick="window.location.href='{{ route('customers.create') }}'">
                    <i class="feather feather-user-plus me-1"></i> Add Customer
                </button>
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Customers Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-primary rounded-circle p-3">
                                <i class="feather feather-users text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Total Customers</h5>
                                <h2 class="fw-bold mb-0">{{ $analytics['totalCustomers'] }}</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <div>
                                <span class="d-block text-muted small">App Active</span>
                                <span class="fw-medium">{{ $analytics['totalAppActiveUsers'] }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Inactive</span>
                                <span class="fw-medium">{{ $analytics['totalCustomers'] - $analytics['totalAppActiveUsers'] }}</span>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 opacity-10">
                            <i class="feather feather-users" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Policies Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-info rounded-circle p-3">
                                <i class="feather feather-file-text text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Total Policies</h5>
                                <h2 class="fw-bold mb-0">{{ $analytics['totalPolicies'] }}</h2>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                style="width: {{ ($analytics['totalActivePolicies'] / max(1, $analytics['totalPolicies'])) * 100 }}%;" 
                                aria-valuenow="{{ ($analytics['totalActivePolicies'] / max(1, $analytics['totalPolicies'])) * 100 }}" 
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mt-2 small text-muted">
                            {{ round(($analytics['totalActivePolicies'] / max(1, $analytics['totalPolicies'])) * 100, 1) }}% policies currently active
                        </div>
                        <div class="position-absolute bottom-0 end-0 opacity-10">
                            <i class="feather feather-file-text" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Policies Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-success rounded-circle p-3">
                                <i class="feather feather-shield text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Active Policies</h5>
                                <h2 class="fw-bold mb-0">{{ $analytics['totalActivePolicies'] }}</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <div>
                                <span class="d-block text-muted small">Avg. per Customer</span>
                                <span class="fw-medium">{{ round($analytics['totalActivePolicies'] / max(1, $analytics['totalCustomers']), 1) }}</span>
                            </div>
                            <div>
                                <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 opacity-10">
                            <i class="feather feather-shield" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expired Policies Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-soft-danger rounded-circle p-3">
                                <i class="feather feather-alert-triangle text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="text-muted fw-normal mb-0">Expired Policies</h5>
                                <h2 class="fw-bold mb-0">{{ $analytics['totalExpiredPolicies'] }}</h2>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                style="width: {{ ($analytics['totalExpiredPolicies'] / max(1, $analytics['totalPolicies'])) * 100 }}%;" 
                                aria-valuenow="{{ ($analytics['totalExpiredPolicies'] / max(1, $analytics['totalPolicies'])) * 100 }}" 
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mt-2 small text-muted">
                            {{ round(($analytics['totalExpiredPolicies'] / max(1, $analytics['totalPolicies'])) * 100, 1) }}% of total policies expired
                        </div>
                        <div class="position-absolute bottom-0 end-0 opacity-10">
                            <i class="feather feather-alert-triangle" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      

        <!-- Customer List Card -->
        <div class="card border-0 shadow-sm rounded-4">
            
            <!-- Customer Table -->
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
                                        @if($user->customer_policies_count > 0)
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
                                        <button type="button" class="btn btn-sm btn-soft-info rounded-pill" data-bs-toggle="modal" data-bs-target="#addressModal{{ $user->id }}">
                                            <i class="feather feather-file-text me-1"></i> view
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('customers.edit', $user->id) }}" class="btn btn-sm btn-icon btn-soft-primary rounded-circle me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Customer">
                                                <i class="feather feather-edit-2"></i>
                                            </a>
                                            <a href="{{ route('customers.changePassword', $user->id) }}" class="btn btn-sm btn-icon btn-soft-warning rounded-circle me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Password">
                                                <i class="feather feather-lock"></i>
                                            </a>
                                           
                                        </div>
                                    </td>
                                </tr>

                                <!-- Enhanced Address Modal -->
                                <div class="modal fade" id="addressModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="feather feather-user me-2"></i>
                                                    {{ $user->name }}'s Details
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
                                                                <address class="mb-0">
                                                                    <strong>{{ $user->name }}</strong><br>
                                                                    {{ $user->address }}<br>
                                                                    {{ $user->city }}, {{ $user->state }}<br>
                                                                    <i class="feather feather-phone me-1 small"></i> {{ $user->mobile_number }}
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
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                        <div>
                                                                            <i class="feather feather-credit-card me-2 text-primary"></i>
                                                                            <strong>Aadhar Card</strong>
                                                                            <p class="mb-0 small text-muted">{{ $user->aadhar_number ?? 'Not provided' }}</p>
                                                                        </div>
                                                                        @if ($user->aadhar_document)
                                                                            <a href="{{ asset('storage/aadhar/' . $user->aadhar_document) }}" target="_blank" class="btn btn-sm btn-soft-primary rounded-pill">
                                                                                <i class="feather feather-download me-1"></i> View
                                                                            </a>
                                                                        @else
                                                                            <span class="badge bg-soft-warning text-warning">Not Uploaded</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                        <div>
                                                                            <i class="feather feather-credit-card me-2 text-info"></i>
                                                                            <strong>PAN Card</strong>
                                                                            <p class="mb-0 small text-muted">{{ $user->pan_number ?? 'Not provided' }}</p>
                                                                        </div>
                                                                        @if ($user->pan_document)
                                                                            <a href="{{ asset('storage/pancard/' . $user->pan_document) }}" target="_blank" class="btn btn-sm btn-soft-primary rounded-pill">
                                                                                <i class="feather feather-download me-1"></i> View
                                                                            </a>
                                                                        @else
                                                                            <span class="badge bg-soft-warning text-warning">Not Uploaded</span>
                                                                        @endif
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

               
            </div>
        </div>
    </div>

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
        .table > :not(caption) > * > * {
            padding: 0.75rem 1rem;
        }

        /* Feather icon integration */
        [class^="feather-"], [class*=" feather-"] {
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