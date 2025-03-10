<?php
// Access analytics data using helper function
$analytics = getCustomerPolicyAnalytics();
?>
<div class="row g-4 mb-4">
    <!-- Total Customers -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-primary">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-primary rounded-circle p-3">
                        <i class="feather feather-users text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Total Customers</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['totalCustomers'] }}</h2>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                    <div>
                        <span class="d-block text-muted small">Total Policies</span>
                        <span class="fw-medium">{{ $analytics['totalPolicies'] }}</span>
                    </div>
                    <div>
                        <span class="d-block text-muted small">App Active Users</span>
                        <span class="fw-medium">{{ $analytics['totalAppActiveUsers'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Policies -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-info">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-info rounded-circle p-3">
                        <i class="feather feather-file-text text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Total Policies</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['totalPolicies'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Policies -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-success">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-success rounded-circle p-3">
                        <i class="feather feather-shield text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Active Policies</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['activePoliciesCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expired Policies -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-danger">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-danger rounded-circle p-3">
                        <i class="feather feather-alert-triangle text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Expired Policies</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['expiredPoliciesCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancelled Policies -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-warning">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-warning rounded-circle p-3">
                        <i class="feather feather-x-circle text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Cancelled Policies</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['cancelledPoliciesCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Policies -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-secondary">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-secondary rounded-circle p-3">
                        <i class="feather feather-clock text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Pending Policies</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['pendingPoliciesCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Policies Expiring This Month -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-info">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-info rounded-circle p-3">
                        <i class="feather feather-calendar text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Policies Expiring This Month</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['policiesExpiringThisMonthCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Policies Expiring in 7 Days -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-soft-danger">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-danger rounded-circle p-3">
                        <i class="feather feather-clock text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-muted fw-normal mb-0">Policies Expiring in 7 Days</h5>
                        <h2 class="fw-bold mb-0">{{ $analytics['policiesExpiringIn7DaysCount'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
