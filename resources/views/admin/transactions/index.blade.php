@extends('admin.layouts.customer')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    .pagination {
        margin-bottom: 0;
    }
    .badge {
        font-size: 85%;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 0.375rem;
    }
    .badge-success {
        background-color: #198754;
        color: #fff;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }
    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
    }
    .search-section {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .policy-table th {
        font-size: 0.85rem;
    }
    .policy-table td {
        font-size: 0.9rem;
    }
    .policy-badge {
        min-width: 24px;
        height: 24px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    .modal-footer {
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    .transaction-info-section {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }
    .info-row:last-child {
        border-bottom: none;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card mb-4">
            <div class="card-header d-flex bg-white py-3 justify-content-between align-items-center">
                <h5 class="mb-0">Transactions List</h5>
                <a href="{{ route('transaction.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Add Transaction
                </a>
            </div>
            <div class="card-body">
                <div class="search-section mb-3">
                    <form method="GET" action="{{ route('admin.transaction') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search Agent</label>
                            <select class="form-select select2" name="agent_id">
                                <option value="">All Agents</option>
                                @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div> --}}
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('admin.transaction') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Agent Name</th>
                                <th>Policies</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                {{-- <th>Status</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>
                                    @if($transaction->agent)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $transaction->agent->name }}</div>
                                            <small class="text-muted">{{ $transaction->agent->phone }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                      
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#policiesModal{{ $transaction->id }}">
                                                <span class="">{{ $transaction->policies->count() }}</span>  <i class="fas fa-list-ul me-1"></i> 
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">₹{{ number_format($transaction->amount_paid, 2) }}</div>
                                </td>
                                <td>
                                    @php
                                        $paymentMethodLabels = [
                                            'cash' => 'Cash',
                                            'google_pay' => 'Google Pay',
                                            'phone_pe' => 'PhonePe',
                                            'credit_card' => 'Credit Card',
                                            'debit_card' => 'Debit Card',
                                            'netbanking' => 'Net Banking',
                                            'paytm' => 'Paytm',
                                            'upi' => 'UPI',
                                            'bank_transfer' => 'Bank Transfer'
                                        ];
                                    @endphp
                                    <span>{{ $paymentMethodLabels[$transaction->payment_method] ?? $transaction->payment_method }}</span>
                                </td>
                                <td>
                                    @if($transaction->transaction_id)
                                    <span class="text-monospace">{{ $transaction->transaction_id }}</span>
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ date('d M, Y', strtotime($transaction->payment_date)) }}</td>
                                {{-- <td>
                                    @if($transaction->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                    @elseif($transaction->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @else
                                    <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $transaction->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $transaction->id }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('transaction.edit', $transaction->id) }}">
                                                    <i class="fas fa-edit me-2 text-info"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionDetailModal{{ $transaction->id }}">
                                                    <i class="fas fa-eye me-2 text-primary"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('transaction.delete', $transaction->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-trash-alt me-2 text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5>No transactions found</h5>
                                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                                        <a href="{{ route('transaction.create') }}" class="btn btn-outline-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add New Transaction
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted mb-0">
                            Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} entries
                        </p>
                    </div>
                    <div>
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Policies Modal -->
@foreach($transactions as $transaction)
<div class="modal fade" id="policiesModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="policiesModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policiesModalLabel{{ $transaction->id }}">
                    <i class="fas fa-file-invoice text-primary me-2"></i>
                    Policies for Transaction #{{ $transaction->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="transaction-info-section mb-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Agent: {{ $transaction->agent->name ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ date('d M, Y', strtotime($transaction->payment_date)) }}</small>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">Total Amount: ₹{{ number_format($transaction->amount_paid, 2) }}</h6>
                            <small class="text-muted">
                                @if($transaction->status == 'completed')
                                <span class="text-success">Completed</span>
                                @elseif($transaction->status == 'pending')
                                <span class="text-warning">Pending</span>
                                @else
                                <span class="text-danger">Failed</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm policy-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Policy No</th>
                                <th>Customer Name</th>
                                <th>Allocated Amount</th>
                                <th>Due Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->policies as $policy)
                            <tr>
                                <td>{{ $policy->policy_no }}</td>
                                <td>{{ $policy->customername ?? 'N/A' }}</td>
                                <td>₹{{ number_format($policy->pivot->amount, 2) }}</td>
                                <td>
                                    @php
                                        $remainingDue = $policy->agent_amount_due - $policy->agent_amount_paid;
                                    @endphp
                                    ₹{{ number_format(max(0, $remainingDue), 2) }}
                                </td>
                                <td>
                                    @if($policy->agent_amount_paid >= $policy->agent_amount_due)
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-warning">Partial</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">₹{{ number_format($transaction->amount_paid, 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('transaction.edit', $transaction->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Transaction
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div class="modal fade" id="transactionDetailModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="transactionDetailModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDetailModalLabel{{ $transaction->id }}">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Transaction Details #{{ $transaction->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-light rounded mb-3">
                    <div class="mb-3">
                        <h6 class="mb-1">Transaction ID #{{ $transaction->id }}</h6>
                        <small class="text-muted">Created: {{ $transaction->created_at->format('d M, Y h:i A') }}</small>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status:</span>
                        <span class="fw-bold">
                            @if($transaction->status == 'completed')
                            <span class="text-success">Completed</span>
                            @elseif($transaction->status == 'pending')
                            <span class="text-warning">Pending</span>
                            @else
                            <span class="text-danger">Failed</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>Agent Information</h6>
                        <p class="mb-0">{{ $transaction->agent->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $transaction->agent->email ?? '' }}</small>
                    </div>
                    <div class="col-6">
                        <h6>Payment Details</h6>
                        <p class="mb-1">Amount: <span class="fw-bold">₹{{ number_format($transaction->amount_paid, 2) }}</span></p>
                        <p class="mb-1">Method: {{ $paymentMethodLabels[$transaction->payment_method] ?? $transaction->payment_method }}</p>
                        @if($transaction->transaction_id)
                        <p class="mb-0">Transaction ID: <span class="text-monospace">{{ $transaction->transaction_id }}</span></p>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>Date Information</h6>
                        <p class="mb-1">Payment Date: {{ date('d M, Y', strtotime($transaction->payment_date)) }}</p>
                        <p class="mb-0">Created By: {{ optional($transaction->creator)->name ?? 'System' }}</p>
                    </div>
                    <div class="col-6">
                        @if($transaction->notes)
                        <h6>Notes</h6>
                        <p class="mb-0">{{ $transaction->notes }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <h6>Policy Allocations ({{ $transaction->policies->count() }})</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Policy No</th>
                                    <th>Customer Name</th>
                                    <th>Allocated Amount</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->policies as $policy)
                                <tr>
                                    <td>{{ $policy->policy_no }}</td>
                                    <td>{{ $policy->customername ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($policy->pivot->amount, 2) }}</td>
                                    <td>
                                        @php
                                            $remainingDue = $policy->agent_amount_due - $policy->agent_amount_paid;
                                        @endphp
                                        ₹{{ number_format(max(0, $remainingDue), 2) }}
                                    </td>
                                    <td>
                                        @if($policy->agent_amount_paid >= $policy->agent_amount_due)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Partial</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">₹{{ number_format($transaction->amount_paid, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('transaction.edit', $transaction->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Transaction
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option"
        });
        
        // Close alert messages after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@endsection