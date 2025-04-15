@extends('admin.layouts.customer')

@section('title', 'Monthly Commissions Dashboard')

@section('content')
<div class="container-fluid py-4">
  @include("admin.commissions.SummaryStatsCards")

    <!-- Top Agents & Monthly Data Section -->
    <div class="row mb-4 TopAgentsMonthlyData_Section">
        <!-- Top Agents Card -->
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white p-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>Top Performing Agents
                        </h5>
                        <span class="badge bg-warning text-dark rounded-pill">Top 5</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (count($topAgents) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($topAgents as $index => $agent)
                                <li class="list-group-item p-3 border-start-0 border-end-0">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <div class="agent-avatar text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; background-color:{{ ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#858796'][$index % 5] }};">
                                                {{ strtoupper(substr($agent->agent->name ?? 'N/A', 0, 1)) }}
                                            </div>
                                            @if($index < 3)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ ['primary', 'success', 'warning'][$index] }}">
                                                {{ $index + 1 }}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold">{{ $agent->agent->name ?? 'N/A' }}</h6>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-2">
                                                    <i class="fa-solid fa-file-signature me-1 text-primary"></i>{{ $agent->policies_count }}
                                                </span>
                                                <span class="text-success fw-semibold">
                                                    <i class="fa-solid fa-indian-rupee-sign me-1"></i>{{ number_format($agent->total_commission, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="progress" style="width: 60px; height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                    style="width: {{ min(100, ($agent->total_commission / ($topAgents[0]->total_commission ?: 1)) * 100) }}%" 
                                                    aria-valuenow="{{ ($agent->total_commission / ($topAgents[0]->total_commission ?: 1)) * 100 }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-4 text-center">
                            <div class="empty-state-container">
                                <i class="fa-solid fa-chart-pie fa-2x text-muted mb-3"></i>
                                <h6>No Data Available</h6>
                                <p class="text-muted small">Try adjusting your filter criteria</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-center border-0 p-3">
                    <a href="{{ route('monthly-commissions', ['year' => $year, 'month' => $month]) }}"
                        class="btn btn-sm btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-users me-1"></i> View All Agents
                    </a>
                </div>
            </div>
        </div>

        <!-- Monthly Commission Records Table -->
        <div class="col-lg-8 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white p-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-table-list text-primary me-2"></i>Monthly Commission Records
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ $monthlyCommissions->count() }} Records</span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-header">
                                <tr>
                                    <th class="border-0"><i class="fa-regular fa-calendar me-1 text-primary"></i>Agent Details</th>
                                    <th class="border-0"><i class="fa-solid fa-sack-dollar me-1 text-primary"></i>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyCommissions as $commission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="calendar-icon me-2 text-center">
                                                    <span class="d-block fw-bold small">{{ strtoupper(substr(date('F', mktime(0, 0, 0, $commission->month, 10)), 0, 3)) }}</span>
                                                    <span class="small text-muted">{{ $commission->year }}</span>
                                                </div>
                                                <div class="agent-avatar mx-2 text-white d-flex align-items-center justify-content-center"
                                                     style="width: 32px; height: 32px; border-radius: 6px; background-color:{{ '#' . substr(md5($commission->agent->name ?? 'N/A'), 0, 6) }};">
                                                    {{ strtoupper(substr($commission->agent->name ?? 'N/A', 0, 1)) }}
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div>{{ $commission->agent->name ?? 'N/A' }}</div>
                                                    <span class="badge bg-light text-dark mt-1">
                                                        <i class="fa-solid fa-file-contract me-1 text-primary"></i>
                                                        {{ number_format($commission->policies_count) }} policies
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="d-flex gap-2 small mb-1">
                                                    <span class="text-muted">Premium:</span>
                                                    <span class="fw-semibold">₹{{ number_format($commission->total_premium, 2) }}</span>
                                                </div>
                                                <div class="d-flex gap-3">
                                                    <div>
                                                        <span class="badge bg-success-light text-success">
                                                            <i class="fa-solid fa-hand-holding-dollar me-1"></i>
                                                            ₹{{ number_format($commission->total_commission, 2) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-info-light text-info">
                                                            <i class="fa-solid fa-circle-dollar-to-slot me-1"></i>
                                                            ₹{{ number_format($commission->total_payout, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">
                                            <i class="fa-solid fa-database-slash fa-2x text-muted mb-3"></i>
                                            <h6>No Commission Records Found</h6>
                                            <p class="text-muted small">Try adjusting your filter criteria</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.auto-submit').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
</script>

<style>
    /* Custom styling for modern look */
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-5px);
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    .bg-success-light {
        background-color: rgba(28, 200, 138, 0.1);
    }
    
    .bg-warning-light {
        background-color: rgba(246, 194, 62, 0.1);
    }
    
    .bg-info-light {
        background-color: rgba(54, 185, 204, 0.1);
    }
    
    .bg-danger-light {
        background-color: rgba(231, 74, 59, 0.1);
    }
    
    .calendar-icon {
        min-width: 40px;
    }
    
    .agent-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* New styles for fixed header and scrollable table */
    .table-container {
        position: relative;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        overflow-x: auto;
        width: 100%;
    }

    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
    }

    .card {
        height: auto !important;
        max-height: calc(100vh - 100px);
        display: flex;
        flex-direction: column;
    }

    .card-body {
        flex: 1;
        overflow: hidden;
    }

    /* Make sure the row takes full height */
    .TopAgentsMonthlyData_Section {
        display: flex;
        min-height: calc(100vh - 150px);
    }

    /* For small screens */
    @media (max-width: 992px) {
        .table-container {
            max-height: 500px;
        }
    }
</style>
@endsection