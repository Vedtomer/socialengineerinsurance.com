@extends('admin.layouts.customer')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-chart-line me-2"></i>Reports Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <!-- Summary Counts Section -->
                        @include('admin.reports.partials.counts_partial')

                        <!-- Report Type Selection -->
                        <ul class="nav nav-pills mb-4" id="reportTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="policy-tab" data-bs-toggle="tab"
                                    data-bs-target="#policy-report" type="button" role="tab">
                                    <i class="fas fa-file-contract me-1"></i> Policy Reports
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="agent-tab" data-bs-toggle="tab" data-bs-target="#agent-report"
                                    type="button" role="tab">
                                    <i class="fas fa-user-tie me-1"></i> Agent Reports
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="customer-tab" data-bs-toggle="tab"
                                    data-bs-target="#customer-report" type="button" role="tab">
                                    <i class="fas fa-users me-1"></i> Customer Reports
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="account-tab" data-bs-toggle="tab"
                                    data-bs-target="#account-report" type="button" role="tab">
                                    <i class="fas fa-money-bill-wave me-1"></i> Account Reports
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="future-tab" data-bs-toggle="tab"
                                    data-bs-target="#future-report" type="button" role="tab">
                                    <i class="fas fa-plus-circle me-1"></i> Other Reports
                                </button>
                            </li>
                        </ul>

                        <!-- Flash Messages -->
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="tab-content" id="reportTabsContent">
                            <!-- Policy Report Tab -->
                            <div class="tab-pane fade show active" id="policy-report" role="tabpanel"
                                aria-labelledby="policy-tab">
                                <div class="card border-light mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-filter me-2"></i>Policy Report Filters
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @include('admin.reports.partials.policy_filters', [
                                            'companies' => $companies,
                                            'agents' => $agents,
                                            'insuranceProducts' => $insuranceProducts,
                                            'paymentTypes' => $paymentTypes,
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Agent Report Tab -->
                            <div class="tab-pane fade" id="agent-report" role="tabpanel" aria-labelledby="agent-tab">
                                <div class="card border-light mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-filter me-2"></i>Agent Report Filters
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @include('admin.reports.partials.user_filters', [
                                            'users' => $agents,
                                            'states' => $states,
                                            'cities' => $cities,
                                            'statuses' => $statuses,
                                            'role' => 'agent',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Report Tab -->
                            <div class="tab-pane fade" id="customer-report" role="tabpanel" aria-labelledby="customer-tab">
                                <div class="card border-light mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-filter me-2"></i>Customer Report Filters
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @include('admin.reports.partials.user_filters', [
                                            'users' => $customers,
                                            'states' => $states,
                                            'cities' => $cities,
                                            'statuses' => $statuses,
                                            'role' => 'customer',
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Account Report Tab -->
                            <div class="tab-pane fade" id="account-report" role="tabpanel" aria-labelledby="account-tab">
                                <div class="card border-light mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-filter me-2"></i>Account Report Filters
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @include('admin.reports.partials.account_filters', [
                                            'agents' => $agents,
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Future Report Tab (placeholder) -->
                            <div class="tab-pane fade" id="future-report" role="tabpanel" aria-labelledby="future-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Additional report types will be added here in the future.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date range to current month
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            // Format dates as YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            // Set default dates for all date inputs
            document.querySelectorAll('input[type="date"][name="from_date"]').forEach(input => {
                input.value = formatDate(firstDay);
            });

            document.querySelectorAll('input[type="date"][name="to_date"]').forEach(input => {
                input.value = formatDate(today);
            });

            // Enable select2 for dropdowns if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    placeholder: "Select an option",
                    allowClear: true
                });
            }
        });
    </script>
@endsection
