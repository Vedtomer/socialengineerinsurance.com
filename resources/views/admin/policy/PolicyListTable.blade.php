<div class="col-12">
    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter text-primary me-2"></i>Filter Policies
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.policy_list') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" name="start_date" class="form-control" 
                                    value="{{ request('start_date', date('Y-m-01')) }}">
                                <span class="input-group-text bg-light">to</span>
                                <input type="date" name="end_date" class="form-control" 
                                    value="{{ request('end_date', date('Y-m-t')) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Agent</label>
                            <select class="form-select js-example-basic-single w-100" name="agent_id" data-placeholder="All Agents">
                                <option value="">All Agents</option>
                                @foreach ($agentData as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   
    <!-- Policy List Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 d-flex align-items-center">
                <i class="fas fa-list text-primary me-2"></i>
                Policy List
            </h5>
            <div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="table-light">
                        <tr>
                            <th>Policy Information</th>
                            <th>Financial Details</th>
                            <th>Policy Date</th>
                            <th>Agent & Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                            <tr>
                                <!-- Policy Information -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-medium me-2">{{ $user->policy_no }}</span>
                                            @if (!empty($user->policy_link))
                                                <a href="{{ $user->policy_link }}" download="{{ $user->policy_link }}"
                                                    class="btn btn-sm btn-outline-primary px-2 py-0">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="text-muted small" title="{{ $user->customername }}">
                                            <i class="fas fa-user text-secondary me-1"></i>
                                            {{ \Illuminate\Support\Str::limit($user->customername, 25) }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-building text-info me-1"></i>
                                            {{ $user->company->name }}
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Financial Details -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="fw-medium">
                                            <i class="fas fa-money-bill-wave text-success me-1"></i>
                                            Premium: ₹{{ number_format($user->premium, 2) }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-percentage text-primary me-1"></i>
                                            Commission: ₹{{ number_format($user->agent_commission, 2) }}
                                        </div>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <i class="fas fa-file-invoice text-secondary me-1"></i>
                                                Net: ₹{{ number_format($user->net_amount, 2) }}
                                            </span>
                                            <span>
                                                <i class="fas fa-receipt text-warning me-1"></i>
                                                GST: ₹{{ number_format($user->gst, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Policy Date -->
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ date('M d, Y', strtotime($user->policy_start_date)) }}
                                    </span>
                                </td>
                                
                                <!-- Combined Agent and Payment By -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <!-- Agent -->
                                        <div class="mb-2">
                                            @if (optional($user->agent)->name)
                                                <span title="{{ $user->agent->name }}" class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                                    {{ Str::limit($user->agent->name, 16) }}
                                                </span>
                                            @else
                                                <select class="form-select form-select-sm js-example-basic-single select2"
                                                    data-control="select2" data-placeholder="Select an option"
                                                    onchange="confirmAgentChange(this); location = this.value;">
                                                    <option value="" selected disabled>Select Agent</option>
                                                    @foreach ($agentData as $record)
                                                        <option
                                                            value="{{ route('updateagentid', ['agent_id' => $record->id, 'royalsundaram_id' => $user->id]) }}">
                                                            {{ Str::limit($record->name, 16) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        
                                        <!-- Payment Method -->
                                        <div>
                                            <span class="badge @if($user->payment_by == 'commission_deducted') bg-success text-white @else bg-light text-dark @endif">
                                                <i class="fas fa-credit-card me-1"></i>
                                                {{ \App\Models\Policy::getPaymentTypes()[$user->payment_by] ?? $user->payment_by }}
                                            </span>
                                        </div>
                                        
                                        <!-- Insurance Product Type -->
                                        <div class="mt-1">
                                            <span class="badge bg-info text-white">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                {{ $user->insuranceProduct->name }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Action Buttons -->
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-primary view-details me-2"
                                            data-bs-toggle="modal" data-bs-target="#viewDetailsModal"
                                            data-policy="{{ $user->policy_no }}"
                                            data-type="{{ $user->insuranceProduct->name }}"
                                            data-customer="{{ $user->customername }}"
                                            data-date="{{ date('M d, Y', strtotime($user->policy_start_date)) }}"
                                            data-net="{{ $user->net_amount }}" data-gst="{{ $user->gst }}"
                                            data-premium="{{ $user->premium }}"
                                            data-commission="{{ $user->agent_commission }}"
                                            data-agent="{{ optional($user->agent)->name }}"
                                            data-company="{{ $user->company->name }}"
                                            data-payment="{{ \App\Models\Policy::getPaymentTypes()[$user->payment_by] ?? $user->payment_by }}"
                                            data-discount="{{ $user->discount }}"
                                            data-payout="{{ $user->payout }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                
                                        <button class="btn btn-sm btn-outline-danger delete-policy"
                                            onclick="policyDelete('{{ $user->id }}')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
  
</div>

