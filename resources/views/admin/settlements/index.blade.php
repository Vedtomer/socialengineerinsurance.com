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

                        <!-- Filter Form -->
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
                                            <label for="start_date" class="form-label">From</label>
                                            <input type="month" name="start_date" id="start_date" class="form-control"
                                                value="{{ $startDate }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="end_date" class="form-label">To</label>
                                            <input type="month" name="end_date" id="end_date" class="form-control"
                                                value="{{ $endDate }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="">All Status</option>
                                                <option value="outstanding"
                                                    {{ $status == 'outstanding' ? 'selected' : '' }}>Outstanding</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="current_month"
                                                    id="current_month" value="1"
                                                    {{ request()->has('current_month') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="current_month">
                                                    Current Month Only
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

                        <!-- Settlements Table -->
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
                                        <th>Total Commission</th>
                                        <th>Premium Due</th>
                                        <th>Pay Later</th>
                                        <th>Amount Paid</th>
                                        <th>Pending</th>
                                        <th>Final Due</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($settlements as $settlement)
                                        <tr>
                                            <td>{{ $settlement->settlement_month->format('M Y') }}</td>
                                            <td>{{ $settlement->agent->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="text-success">
                                                    <i class="fas fa-coins me-1"></i>
                                                    {{ number_format($settlement->total_commission, 2) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($settlement->total_premium_due, 2) }}</td>
                                            <td>{{ number_format($settlement->pay_later_amount, 2) }}</td>
                                            <td>
                                                <span class="text-primary">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ number_format($settlement->amount_paid, 2) }}
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
                                                        {{ number_format($settlement->final_amount_due, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-success fw-bold">
                                                        <i class="fas fa-check-double me-1"></i>
                                                        {{ number_format($settlement->final_amount_due, 2) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($settlement->final_amount_due > 0)
                                                    <span class="badge bg-danger">Outstanding</span>
                                                @else
                                                    <span class="badge bg-success">Cleared</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailsModal{{ $settlement->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Details Modal -->
                                                <div class="modal fade" id="detailsModal{{ $settlement->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="detailsModalLabel{{ $settlement->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title"
                                                                    id="detailsModalLabel{{ $settlement->id }}">
                                                                    Settlement Details -
                                                                    {{ $settlement->settlement_month->format('M Y') }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <h6>Agent Information</h6>
                                                                        <p>
                                                                            <i class="fas fa-user me-2"></i>
                                                                            <strong>Name:</strong>
                                                                            {{ $settlement->agent->name ?? 'N/A' }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6>Settlement Period</h6>
                                                                        <p>
                                                                            <i class="fas fa-calendar-alt me-2"></i>
                                                                            <strong>Month:</strong>
                                                                            {{ $settlement->settlement_month->format('F Y') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th colspan="2"
                                                                                            class="text-center">Settlement
                                                                                            Details</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="50%"><strong>Total
                                                                                                Commission</strong></td>
                                                                                        <td>{{ number_format($settlement->total_commission, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Total Premium
                                                                                                Due</strong></td>
                                                                                        <td>{{ number_format($settlement->total_premium_due, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Pay Later
                                                                                                Amount</strong></td>
                                                                                        <td>{{ number_format($settlement->pay_later_amount, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Pay Later With
                                                                                                Adjustment</strong></td>
                                                                                        <td>{{ number_format($settlement->pay_later_with_adjustment_amount, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Previous Month
                                                                                                Commission</strong></td>
                                                                                        <td>{{ number_format($settlement->previous_month_commission, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Adjusted
                                                                                                Commission</strong></td>
                                                                                        <td>{{ number_format($settlement->adjusted_commission, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Carry Forward
                                                                                                Due</strong></td>
                                                                                        <td>{{ number_format($settlement->carry_forward_due, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Amount Paid</strong>
                                                                                        </td>
                                                                                        <td class="text-success">
                                                                                            {{ number_format($settlement->amount_paid, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><strong>Pending Amount</strong>
                                                                                        </td>
                                                                                        <td class="text-warning">
                                                                                            {{ number_format($settlement->pending_amount, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="table-secondary">
                                                                                        <td><strong>Final Amount
                                                                                                Due</strong></td>
                                                                                        <td
                                                                                            class="{{ $settlement->final_amount_due > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                                                                            {{ number_format($settlement->final_amount_due, 2) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if ($settlement->notes)
                                                                    <div class="row mt-3">
                                                                        <div class="col-12">
                                                                            <div class="alert alert-info">
                                                                                <h6><i class="fas fa-sticky-note me-2"></i>
                                                                                    Notes:</h6>
                                                                                <p class="mb-0">{{ $settlement->notes }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $settlements->withQueryString()->links('pagination::bootstrap-5') }}
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
            // Disable current month checkbox when date range is selected
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const currentMonthCheckbox = document.getElementById('current_month');

            function updateCurrentMonthCheckbox() {
                if (startDateInput.value || endDateInput.value) {
                    currentMonthCheckbox.checked = false;
                    currentMonthCheckbox.disabled = true;
                } else {
                    currentMonthCheckbox.disabled = false;
                }
            }

            startDateInput.addEventListener('change', updateCurrentMonthCheckbox);
            endDateInput.addEventListener('change', updateCurrentMonthCheckbox);

            // Check on page load
            updateCurrentMonthCheckbox();

            // Disable date range when current month is checked
            currentMonthCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    startDateInput.value = '';
                    endDateInput.value = '';
                    startDateInput.disabled = true;
                    endDateInput.disabled = true;
                } else {
                    startDateInput.disabled = false;
                    endDateInput.disabled = false;
                }
            });

            // Check on page load
            if (currentMonthCheckbox.checked) {
                startDateInput.disabled = true;
                endDateInput.disabled = true;
            }
        });
    </script>
@endsection
