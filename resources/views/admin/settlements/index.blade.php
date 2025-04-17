@extends('admin.layouts.customer')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-money-check-dollar me-2"></i> Agent Monthly Settlements
                        </h5>
                    </div>

                    <div class="card-body">
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase mb-1">Total Due</h6>
                                                <h3 class="mb-0">{{ number_format($totalDue, 2) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-white text-info p-3">
                                                <i class="fas fa-wallet fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase mb-1">Total Paid</h6>
                                                <h3 class="mb-0">{{ number_format($totalPaid, 2) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-white text-success p-3">
                                                <i class="fas fa-hand-holding-dollar fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase mb-1">Total Pending</h6>
                                                <h3 class="mb-0">{{ number_format($totalPending, 2) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-white text-warning p-3">
                                                <i class="fas fa-hourglass-half fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Filter Form -->
                        <form action="{{ route('agent.settlements.index') }}" method="GET" class="mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filter Settlements</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="agent_id" class="form-label">Agent</label>
                                            <select name="agent_id" id="agent_id" class="form-select">
                                                <option value="">All Agents</option>
                                                @foreach ($agents as $agent)
                                                    <option value="{{ $agent->id }}"
                                                        {{ $agentId == $agent->id ? 'selected' : '' }}>
                                                        {{ $agent->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="year" class="form-label">Year</label>
                                            <select name="year" id="year" class="form-select">
                                                <option value="">All Years</option>
                                                @foreach ($availableYears as $yr)
                                                    <option value="{{ $yr }}" {{ isset($year) && $year == $yr ? 'selected' : '' }}>
                                                        {{ $yr }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="month" class="form-label">Month</label>
                                            <select name="month" id="month" class="form-select">
                                                <option value="">All Months</option>
                                                @foreach (range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ isset($month) && $month == $m ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Date Range</label>
                                            <div class="input-group">
                                                <input type="month" name="start_date" id="start_date" class="form-control"
                                                    value="{{ $startDate }}" placeholder="From">
                                                <input type="month" name="end_date" id="end_date" class="form-control"
                                                    value="{{ $endDate }}" placeholder="To">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check me-3 d-inline-block">
                                                <input class="form-check-input" type="checkbox" name="current_month"
                                                    id="current_month" value="1"
                                                    {{ request()->has('current_month') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="current_month">
                                                    Current Month Only
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="checkbox" name="show_grouped"
                                                    id="show_grouped" value="1"
                                                    {{ request()->has('show_grouped') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_grouped">
                                                    Group by Month
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search me-2"></i> Apply Filters
                                            </button>
                                            <a href="{{ route('agent.settlements.index') }}"
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-undo me-2"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @if(isset($isGrouped) && $isGrouped)
                            <!-- Monthly Grouped Display -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> 
                                        Displaying data grouped by month ({{ $groupedSettlements->count() }} months).
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly Cards -->
                            @foreach($groupedSettlements as $monthYear => $monthSettlements)
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-calendar-alt me-2"></i> {{ $monthYear }}
                                            </h5>
                                            <span class="badge bg-light text-dark">
                                                {{ $monthSettlements->count() }} {{ Str::plural('settlement', $monthSettlements->count()) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Monthly Summary -->
                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <h6 class="text-muted mb-2">Total Commission</h6>
                                                        <h4 class="mb-0 text-primary">
                                                            {{ number_format($monthlyTotals[$monthYear]['total_commission'], 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <h6 class="text-muted mb-2">Total Premium</h6>
                                                        <h4 class="mb-0 text-primary">
                                                            {{ number_format($monthlyTotals[$monthYear]['total_premium_due'], 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <h6 class="text-muted mb-2">Final Amount Due</h6>
                                                        <h4 class="mb-0 {{ $monthlyTotals[$monthYear]['final_amount_due'] > 0 ? 'text-danger' : 'text-success' }}">
                                                            {{ number_format($monthlyTotals[$monthYear]['final_amount_due'], 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Monthly Settlements Table -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Agent</th>
                                                        <th>Premium Due</th>
                                                        <th>Commission</th>
                                                        <th>Amount Paid</th>
                                                        <th>Pending</th>
                                                        <th>Final Due</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($monthSettlements as $settlement)
                                                        <tr>
                                                            <td>{{ $settlement->agent->name ?? 'N/A' }}</td>
                                                            <td>{{ number_format($settlement->total_premium_due) }}</td>
                                                            <td>
                                                                <span class="text-success">
                                                                    <i class="fas fa-coins me-1"></i>
                                                                    {{ number_format($settlement->total_commission) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-primary">
                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                    {{ number_format($settlement->amount_paid) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if ($settlement->pending_amount > 0)
                                                                    <span class="text-warning">
                                                                        <i class="fas fa-clock me-1"></i>
                                                                        {{ number_format($settlement->pending_amount, 2) }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">0.00</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($settlement->final_amount_due > 0)
                                                                    <span class="text-danger fw-bold">
                                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                                        {{ number_format($settlement->final_amount_due) }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-success fw-bold">
                                                                        <i class="fas fa-check-double me-1"></i>
                                                                        {{ number_format($settlement->final_amount_due) }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailsModal{{ $settlement->id }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>

                                                                <!-- Details Modal (Keep existing modal code here) -->
                                                                <div class="modal fade" id="detailsModal{{ $settlement->id }}"
                                                                    tabindex="-1"
                                                                    aria-labelledby="detailsModalLabel{{ $settlement->id }}"
                                                                    aria-hidden="true">
                                                                    <!-- Keep existing modal content -->
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary text-white">
                                                                                <h5 class="modal-title"
                                                                                    id="detailsModalLabel{{ $settlement->id }}">
                                                                                    Settlement Details - {{ date('F Y', mktime(0, 0, 0, $settlement->month, 1, $settlement->year)) }}
                                                                                </h5>
                                                                                <button type="button" class="btn-close btn-close-white"
                                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Keep existing modal body content -->
                                                                                <!-- I'm keeping the same modal body structure as in the original file -->
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Standard Settlements Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>
                                                <a href="{{ route('agent.settlements.index', array_merge(request()->all(), ['sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                                    class="text-white text-decoration-none">
                                                    Month
                                                    @if ($sortDirection == 'asc')
                                                        <i class="fas fa-sort-up ms-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ms-1"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Agent</th>
                                            <th>Premium Due</th>
                                            <th>Commission</th>
                                            <th>Amount Paid</th>
                                            <th>Pending</th>
                                            <th>Carry Forward Due</th>
                                            <th>Final Due</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($settlements as $settlement)
                                            <tr>
                                                <td>{{ date('F Y', mktime(0, 0, 0, $settlement->month, 1, $settlement->year)) }}</td>
                                                <td>{{ $settlement->agent->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($settlement->total_premium_due) }}</td>
                                                <td>
                                                    <span class="text-success">
                                                        <i class="fas fa-coins me-1"></i>
                                                        {{ number_format($settlement->total_commission) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-primary">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ number_format($settlement->amount_paid) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($settlement->pending_amount > 0)
                                                        <span class="text-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ number_format($settlement->pending_amount, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">0.00</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($settlement->carry_forward_due > 0)
                                                        <span class="text-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ number_format($settlement->carry_forward_due) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">0.00</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($settlement->final_amount_due > 0)
                                                        <span class="text-danger fw-bold">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ number_format($settlement->final_amount_due) }}
                                                        </span>
                                                    @else
                                                        <span class="text-success fw-bold">
                                                            <i class="fas fa-check-double me-1"></i>
                                                            {{ number_format($settlement->final_amount_due) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailsModal{{ $settlement->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Details Modal -->
                                                    <!-- Keep existing modal code here -->
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-search me-2"></i>
                                                        No settlements found with the current filters.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if(!isset($isGrouped) || !$isGrouped)
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $settlements->withQueryString()->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Disable current month checkbox when date range is selected
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const currentMonthCheckbox = document.getElementById('current_month');
            const yearSelect = document.getElementById('year');
            const monthSelect = document.getElementById('month');

            function updateCurrentMonthCheckbox() {
                if (startDateInput.value || endDateInput.value || yearSelect.value || monthSelect.value) {
                    currentMonthCheckbox.checked = false;
                    currentMonthCheckbox.disabled = true;
                } else {
                    currentMonthCheckbox.disabled = false;
                }
            }

            startDateInput.addEventListener('change', updateCurrentMonthCheckbox);
            endDateInput.addEventListener('change', updateCurrentMonthCheckbox);
            yearSelect.addEventListener('change', updateCurrentMonthCheckbox);
            monthSelect.addEventListener('change', updateCurrentMonthCheckbox);

            // Check on page load
            updateCurrentMonthCheckbox();

            // Disable date range when current month is checked
            currentMonthCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    startDateInput.value = '';
                    endDateInput.value = '';
                    yearSelect.value = '';
                    monthSelect.value = '';
                    startDateInput.disabled = true;
                    endDateInput.disabled = true;
                    yearSelect.disabled = true;
                    monthSelect.disabled = true;
                } else {
                    startDateInput.disabled = false;
                    endDateInput.disabled = false;
                    yearSelect.disabled = false;
                    monthSelect.disabled = false;
                }
            });

            // Check on page load
            if (currentMonthCheckbox.checked) {
                startDateInput.disabled = true;
                endDateInput.disabled = true;
                yearSelect.disabled = true;
                monthSelect.disabled = true;
            }

            // Handle year/month selection vs date range
            yearSelect.addEventListener('change', function() {
                if (this.value) {
                    startDateInput.value = '';
                    endDateInput.value = '';
                }
            });

            monthSelect.addEventListener('change', function() {
                if (this.value) {
                    startDateInput.value = '';
                    endDateInput.value = '';
                }
            });

            // Handle date range vs year/month selection
            startDateInput.addEventListener('change', function() {
                if (this.value) {
                    yearSelect.value = '';
                    monthSelect.value = '';
                }
            });

            endDateInput.addEventListener('change', function() {
                if (this.value) {
                    yearSelect.value = '';
                    monthSelect.value = '';
                }
            });
        });
    </script>
@endsection