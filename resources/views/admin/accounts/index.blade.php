@extends('admin.layouts.customer')

@section('title', 'Accounts Management')

@section('content')
<div class="container-fluid px-4">
   
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Accounts</li>
    </ol>

    <!-- Flash Messages -->
    <div id="alert-container">
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
    </div>

    <!-- Filter Card -->
    <div class="card mb-4 shadow-sm rounded-lg">
        <div class="card-body pb-0">
            <form id="filter-form" method="GET" action="{{ route('account.management') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="user_id" class="form-label fw-bold">
                            <i class="fas fa-user-tie text-primary me-2"></i> Agent
                        </label>
                        <select class="form-select select2-agent" id="user_id" name="user_id">
                            <option value="">All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('user_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="from_date" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-success me-2"></i> From Date
                        </label>
                        <input type="date" class="form-control" id="from_date" name="from_date"
                               value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-danger me-2"></i> To Date
                        </label>
                        <input type="date" class="form-control" id="to_date" name="to_date"
                               value="{{ request('to_date') }}">
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('account.management') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-circle-left me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Accounts List Card -->
    <div class="card shadow-sm rounded-lg mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i> Accounts List</h5>
            <button class="btn btn-light btn btn-dark" data-bs-toggle="modal" data-bs-target="#accountModal">
                <i class="fas fa-plus me-1"></i> Add Account
            </button>
        </div>
        <div class="card-body">
            @if($accounts->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No account records found.
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->agent->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($account->amount_paid) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $account->payment_method)) }}</td>
                            <td>{{ $account->payment_method === 'cash' ? 'N/A' : ($account->transaction_id ?? 'N/A') }}</td>
                            <td>{{ $account->payment_date->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('account.management', ['edit' => $account->id]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger delete-account"
                                        data-id="{{ $account->id }}"
                                        data-name="{{ $account->agent->name ?? 'this account' }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No accounts found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $accounts->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Account Modal (Add / Edit) -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-wallet me-2"></i> <span id="modalTitle">{{ $editAccount ? 'Edit Account' : 'Add New Account' }}</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="accountForm" method="POST" action="{{ route('account.store') }}">
                @csrf
                <input type="hidden" name="id" id="accountId" value="{{ $editAccount ? $editAccount->id : '' }}">
                <div class="modal-body">
                    <!-- Display validation errors at the top of form -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="modal_user_id" class="form-label fw-bold">
                            <i class="fas fa-user-tie text-primary me-2"></i> Agent
                        </label>
                        <select class="form-select select2-modal-agent @error('user_id') is-invalid @enderror" 
                                id="modal_user_id" name="user_id" required>
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ ($editAccount && $editAccount->user_id == $agent->id) || old('user_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="amount_paid" class="form-label fw-bold">
                                <i class="fas fa-coins text-success me-2"></i> Amount Paid
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control @error('amount_paid') is-invalid @enderror" 
                                       id="amount_paid" name="amount_paid" step="0.01" min="0" 
                                       value="{{ $editAccount ? $editAccount->amount_paid : old('amount_paid') }}" required>
                                @error('amount_paid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method" class="form-label fw-bold">
                                <i class="fas fa-credit-card text-info me-2"></i> Payment Method
                            </label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method"
                                    required onchange="toggleTransactionIdField()">
                                <option value="">Select Method</option>
                                <option value="cash" {{ ($editAccount && $editAccount->payment_method == 'cash') || old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <optgroup label="Online">
                                    <option value="google_pay" {{ ($editAccount && $editAccount->payment_method == 'google_pay') || old('payment_method') == 'google_pay' ? 'selected' : '' }}>Google Pay</option>
                                    <option value="phone_pe" {{ ($editAccount && $editAccount->payment_method == 'phone_pe') || old('payment_method') == 'phone_pe' ? 'selected' : '' }}>PhonePe</option>
                                    <option value="credit_card" {{ ($editAccount && $editAccount->payment_method == 'credit_card') || old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ ($editAccount && $editAccount->payment_method == 'debit_card') || old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="netbanking" {{ ($editAccount && $editAccount->payment_method == 'netbanking') || old('payment_method') == 'netbanking' ? 'selected' : '' }}>Netbanking</option>
                                    <option value="paytm" {{ ($editAccount && $editAccount->payment_method == 'paytm') || old('payment_method') == 'paytm' ? 'selected' : '' }}>Paytm</option>
                                    <option value="upi" {{ ($editAccount && $editAccount->payment_method == 'upi') || old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank_transfer" {{ ($editAccount && $editAccount->payment_method == 'bank_transfer') || old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </optgroup>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="payment_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-check text-success me-2"></i> Payment Date
                            </label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" 
                                   value="{{ $editAccount ? $editAccount->payment_date->format('Y-m-d') : old('payment_date') }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="transaction_id_container">
                            <label for="transaction_id" class="form-label fw-bold">
                                <i class="fas fa-receipt text-warning me-2"></i> Transaction ID
                            </label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" 
                                   value="{{ $editAccount ? $editAccount->transaction_id : old('transaction_id') }}">
                            @error('transaction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Hidden status field - set default to completed -->
                    <input type="hidden" name="status" value="completed">

                    <div class="mb-3 mt-3">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-sticky-note text-secondary me-2"></i> Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ $editAccount ? $editAccount->notes : old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> <span id="saveButtonText">{{ $editAccount ? 'Update' : 'Save' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the account for <strong id="delete_account_name"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>This cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form id="deleteAccountForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default date
    if (!document.getElementById('payment_date').value) {
        document.getElementById('payment_date').valueAsDate = new Date();
    }

    // Select2 for filter (with search)
    $('.select2-agent').select2({
        placeholder: "Select agent...",
        allowClear: true,
        width: '100%',
        dropdownParent: $('#filter-form')
    });

    // Select2 for modal (with search)
    $('.select2-modal-agent').select2({
        placeholder: "Search and select agent...",
        allowClear: true,
        width: '100%',
        dropdownParent: $('#accountModal .modal-content')
    });

    // Check if we need to show the modal for editing
    @if($editAccount)
        new bootstrap.Modal(document.getElementById('accountModal')).show();
    @endif

    // Check if we need to show the modal (for validation errors)
    @if ($errors->any())
        new bootstrap.Modal(document.getElementById('accountModal')).show();
    @endif

    // Apply initial transaction field state
    toggleTransactionIdField();

    // Delete logic
    $('.delete-account').on('click', function() {
        $('#delete_account_name').text(this.dataset.name);
        $('#deleteAccountForm').attr('action', `{{ route('account.delete', '') }}/${this.dataset.id}`);

        new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert:not(.alert-validation)');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Toggle transaction ID field
function toggleTransactionIdField() {
    const pm = document.getElementById('payment_method').value;
    const ctr = document.getElementById('transaction_id_container');
    const transactionIdField = document.getElementById('transaction_id');
    
    if (pm === 'cash') { 
        ctr.style.display = 'none'; 
        transactionIdField.required = false;
        transactionIdField.value = ''; 
    } else { 
        ctr.style.display = 'block'; 
        transactionIdField.required = true; 
    }
}
</script>
@endsection
