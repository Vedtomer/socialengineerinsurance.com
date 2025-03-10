<?php
// Access analytics data using helper function
$analytics = getCustomerAnalytics();
?>
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('customers.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-primary">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary rounded-circle p-3"> <i class="feather feather-users text-white"></i> </div>
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
                        <i class="feather feather-users text-primary" style="font-size: 5rem;"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-md-6">
        <a href="{{ route('customer-policies.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-info">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info rounded-circle p-3"> <i class="feather feather-file-text text-white"></i> </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-muted fw-normal mb-0">Total Policies</h5>
                            <h2 class="fw-bold mb-0">{{ $analytics['totalPolicies'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-md-6">
        <a href="{{ route('customer-policies.index', ['status' => 'active']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-success">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success rounded-circle p-3"> <i class="feather feather-shield text-white"></i> </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-muted fw-normal mb-0">Active Policies</h5>
                            <h2 class="fw-bold mb-0">{{ $analytics['totalActivePolicies'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-md-6">
        <a href="{{ route('customer-policies.index', ['status' => 'expired']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-danger">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger rounded-circle p-3"> <i class="feather feather-alert-triangle text-white"></i> </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-muted fw-normal mb-0">Expired Policies</h5>
                            <h2 class="fw-bold mb-0">{{ $analytics['totalExpiredPolicies'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>