@extends('admin.layouts.customer')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.transaction') }}">Transactions</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($transaction) ? 'Edit' : 'Add' }} Transaction</li>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        border: 1px solid #dee2e6;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    .btn-secondary {
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }
    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.25rem + 2px);
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
    }
    .allocation-details {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    .policy-item {
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e9ecef;
        background-color: white;
    }
    .policy-item:last-child {
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
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="main-card mb-4 card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">{{ isset($transaction) ? 'Edit' : 'Add' }} Transaction</h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ isset($transaction) ? route('update.transaction', $transaction->id) : route('add.transaction') }}" enctype="multipart/form-data" id="transactionForm">
                    @csrf
                    @if(isset($transaction))
                        @method('PUT')
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label" for="agent_id">Agent</label>
                            <select class="form-select js-example-basic-single select2" data-control="select2" id="agent_id" name="agent_id" required {{ isset($transaction) ? 'disabled' : '' }}>
                                <option value="">Select Agent</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" 
                                        {{ (isset($transaction) && $transaction->agent_id == $agent->id) || 
                                           (request()->has('agent_id') && request()->agent_id == $agent->id) ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(isset($transaction))
                                <input type="hidden" name="agent_id" value="{{ $transaction->agent_id }}">
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" for="total_due">Total Amount Due</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="text" class="form-control" id="total_due" readonly value="{{ isset($totalDue) ? number_format($totalDue, 2) : '0.00' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label" for="payment_method">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required onchange="toggleTransactionIDInput()">
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ isset($transaction) && $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                <optgroup label="Online">
                                    <option value="google_pay" {{ isset($transaction) && $transaction->payment_method == 'google_pay' ? 'selected' : '' }}>Google Pay</option>
                                    <option value="phone_pe" {{ isset($transaction) && $transaction->payment_method == 'phone_pe' ? 'selected' : '' }}>PhonePe</option>
                                    <option value="credit_card" {{ isset($transaction) && $transaction->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ isset($transaction) && $transaction->payment_method == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="netbanking" {{ isset($transaction) && $transaction->payment_method == 'netbanking' ? 'selected' : '' }}>Netbanking</option>
                                    <option value="paytm" {{ isset($transaction) && $transaction->payment_method == 'paytm' ? 'selected' : '' }}>Paytm</option>
                                    <option value="upi" {{ isset($transaction) && $transaction->payment_method == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank_transfer" {{ isset($transaction) && $transaction->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </optgroup>
                            </select>
                        </div>
                        
                        <div class="col-md-6" id="transaction_id_field" style="{{ isset($transaction) && $transaction->payment_method != 'cash' ? 'display:block' : 'display:none' }}">
                            <label class="form-label" for="transaction_id">Transaction ID</label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ isset($transaction) ? $transaction->transaction_id : '' }}">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label" for="amount_paid">Amount Paid</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid" required value="{{ isset($transaction) ? $transaction->amount_paid : '' }}" oninput="calculateAllocation()">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" for="payment_date">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" required value="{{ isset($transaction) ? $transaction->payment_date : date('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($transaction) ? $transaction->notes : '' }}</textarea>
                    </div>

                    <div class="mb-4 allocation-details" id="allocationDetails" style="display: none;">
                        <h6 class="mb-3">Automatic Payment Allocation</h6>
                        <div id="policyAllocationContainer">
                            <!-- Dynamic policy allocation will be shown here -->
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <span>Total Amount Paid:</span>
                            <span id="totalAmountDisplay">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Amount Remaining:</span>
                            <span id="remainingAfterTransaction">₹0.00</span>
                        </div>
                        
                        <!-- Hidden input to store allocation data -->
                        <input type="hidden" name="allocation_data" id="allocation_data" value="">
                    </div>
                    
                    {{-- <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="completed" {{ isset($transaction) && $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ isset($transaction) && $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ isset($transaction) && $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div> --}}
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4 me-2">
                            {{ isset($transaction) ? 'Update' : 'Submit' }}
                        </button>
                        <a href="{{ route('admin.transaction') }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Store policies data globally
    let agentPolicies = [];
    let totalAgentDue = 0;

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option"
        });

        // Handle agent selection
        $('#agent_id').on('change', function() {
            const agentId = $(this).val();
            if (agentId) {
                fetchAgentPolicies(agentId);
            } else {
                resetAllocationView();
                $('#total_due').val('0.00');
                agentPolicies = [];
                totalAgentDue = 0;
            }
        });

        // Initialize agent if already selected
        const selectedAgentId = $('#agent_id').val();
        if (selectedAgentId) {
            fetchAgentPolicies(selectedAgentId);
        }

        // Set today's date as default if not editing
        @if(!isset($transaction))
        document.getElementById("payment_date").value = new Date().toISOString().split('T')[0];
        @endif

        // Calculate allocation when amount changes
        $('#amount_paid').on('input', function() {
            calculateAllocation();
        });
    });

    function fetchAgentPolicies(agentId) {
        $.ajax({
            url: `{{ route('get.agent.policies') }}`,
            type: 'GET',
            data: { agent_id: agentId },
            success: function(response) {
                agentPolicies = response.policies;
                totalAgentDue = 0;
                
                // Calculate total due amount
                agentPolicies.forEach(policy => {
                    const due = parseFloat(policy.agent_amount_due) || 0;
                    const paid = parseFloat(policy.agent_amount_paid) || 0;
                    const remaining = due - paid;
                    totalAgentDue += remaining > 0 ? remaining : 0;
                });
                
                $('#total_due').val(totalAgentDue.toFixed(2));
                
                // Sort policies by creation date (assuming older policies should be paid first)
                agentPolicies.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                
                // Calculate allocation
                calculateAllocation();
            },
            error: function(xhr) {
                console.error('Error fetching policies:', xhr);
                resetAllocationView();
            }
        });
    }

    function calculateAllocation() {
        const amountPaid = parseFloat($('#amount_paid').val()) || 0;
        const allocationDetails = $('#allocationDetails');
        const policyAllocationContainer = $('#policyAllocationContainer');
        
        // If no amount or no policies, hide details
        if (amountPaid <= 0 || agentPolicies.length === 0) {
            allocationDetails.hide();
            return;
        }
        
        // Clear container
        policyAllocationContainer.empty();
        
        // Show allocation details
        allocationDetails.show();
        
        let remainingPayment = amountPaid;
        let remainingTotal = totalAgentDue;
        let allocationData = [];
        
        // Process each policy for allocation
        for (const policy of agentPolicies) {
            const due = parseFloat(policy.agent_amount_due) || 0;
            const paid = parseFloat(policy.agent_amount_paid) || 0;
            const policyRemaining = due - paid;
            
            // Skip if this policy has nothing remaining
            if (policyRemaining <= 0) continue;
            
            // Calculate how much we can allocate to this policy
            let allocatedAmount = 0;
            
            if (remainingPayment > 0) {
                allocatedAmount = Math.min(policyRemaining, remainingPayment);
                remainingPayment -= allocatedAmount;
                remainingTotal -= allocatedAmount;
            }
            
            // Store allocation data
            if (allocatedAmount > 0) {
                allocationData.push({
                    policy_id: policy.id,
                    amount: allocatedAmount
                });
            }
            
            // Create policy item in the UI
            const policyItem = $('<div class="policy-item"></div>');
            policyItem.html(`
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${policy.policy_no}</strong> - ${policy.customername}
                    </div>
                    <span class="badge ${allocatedAmount > 0 ? 'badge-success' : 'badge-warning'}">
                        ${allocatedAmount > 0 ? 'Allocating' : 'Pending'}
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Due: ₹${policyRemaining.toFixed(2)}</span>
                    <span>Allocated: ₹${allocatedAmount.toFixed(2)}</span>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: ${Math.min(100, (allocatedAmount / policyRemaining) * 100)}%" 
                         aria-valuenow="${allocatedAmount}" aria-valuemin="0" aria-valuemax="${policyRemaining}"></div>
                </div>
            `);
            
            policyAllocationContainer.append(policyItem);
            
            // Stop if we've allocated all the payment
            if (remainingPayment <= 0) break;
        }
        
        // Update total display
        $('#totalAmountDisplay').text(`₹${amountPaid.toFixed(2)}`);
        $('#remainingAfterTransaction').text(`₹${Math.max(0, remainingTotal).toFixed(2)}`);
        
        // Store allocation data in hidden input
        $('#allocation_data').val(JSON.stringify(allocationData));
    }

    function toggleTransactionIDInput() {
        const paymentMethod = document.getElementById("payment_method").value;
        const transactionIDField = document.getElementById("transaction_id_field");
        
        if (paymentMethod === "cash") {
            transactionIDField.style.display = "none";
            document.getElementById("transaction_id").value = "";
        } else {
            transactionIDField.style.display = "block";
        }
    }

    function resetAllocationView() {
        $('#allocationDetails').hide();
        $('#policyAllocationContainer').empty();
        $('#totalAmountDisplay').text('₹0.00');
        $('#remainingAfterTransaction').text('₹0.00');
        $('#allocation_data').val('');
    }
</script>
@endsection