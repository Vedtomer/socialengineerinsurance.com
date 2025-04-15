<div class="col-12">
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
                        <button class="btn btn-sm btn-outline-success ms-2">
                            <i class="fas fa-file-excel me-2"></i>Export
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
                                        <!-- Combined Policy No and Customer Name -->
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
                                            </div>
                                        </td>
                                        
                                        <!-- Combined Premium and Commission -->
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="fw-medium">
                                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                                    {{ $user->premium }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-percentage text-primary me-1"></i>
                                                    Commission: {{ $user->agent_commission }}
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
                                                <div class="mb-1">
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
                                                
                                                <!-- Payment By -->
                                                <div>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-credit-card me-1"></i>
                                                        {{ \App\Models\Policy::getPaymentTypes()[$user->payment_by] ?? $user->payment_by }}
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
                                                    data-company="{{ $user->Company->name }}"
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

        