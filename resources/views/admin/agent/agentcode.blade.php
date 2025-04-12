@extends('admin.layouts.customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#"> Agent</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-cog"></i>  Codes Management</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <!-- Commission Filter Card -->
            <div class="card mb-4 shadow-sm rounded-lg">
                <div class="card-body pb-0">
                  
            
                    <div class="collapse show" id="filterCollapse">
                        <form id="filterForm" action="{{ route('commission.management') }}" method="GET">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-primary me-2"></i> Search Agent
                                    </label>
                                    <select class="form-select select2-agent" name="agent_id">
                                        <option value="">All Agents</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            

            <!-- Commission Listing Card -->
            <div class="card shadow-sm rounded-lg">
                <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i> Agent Codes</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('commission.management', ['sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}"
                            class="btn btn-light btn-sm me-2">
                            <i class="fas fa-sort-alpha-{{ request('sort') === 'asc' ? 'down' : 'up' }}"></i>
                        </a>
                        <button class="btn btn-danger btn-sm me-2" id="deleteSelectedBtn" disabled>
                            <i class="fas fa-trash-alt me-1"></i> Delete Selected
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#commissionModal">
                            <i class="fas fa-plus me-1"></i> Add Commission
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($agentsWithCommissions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No commission records found.
                        </div>
                    @endif
                    
                    @foreach ($agentsWithCommissions as $agent)
                   
                        @if(!empty($agent->agentCodes) && $agent->agentCodes->count() > 0)
                      
                        <div class="commission-group mb-4">
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary p-2 rounded-circle me-1">
                                        <i class="fas fa-user-tie text-primary fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $agent->name }}</h6>
                                        <span class="text-muted small">{{ $agent->mobile_number ?? 'No phone' }}</span>
                                    </div>
                                </div>
                               
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">
                                                <div class="form-check">
                                                    <input class="form-check-input group-checkbox" type="checkbox">
                                                </div>
                                            </th>
                                            <th style="width: 150px;">Code</th>
                                            <th style="width: 180px;">Product</th>
                                            <th style="width: 180px;">Company</th>
                                            <th style="width: 100px;">Commission</th>
                                            <th style="width: 160px;">Payment Type</th>
                                            <th style="width: 80px;">GST</th>
                                            <th style="width: 80px;">Discount</th>
                                            <th style="width: 80px;">Payout</th>
                                            <th style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agent->agentCodes as $commission)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input commission-checkbox"
                                                            type="checkbox" value="{{ $commission->id }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-info me-2">{{ $commission->code }}</span>
                                                        <button class="btn btn-sm btn-icon btn-outline-secondary copy-code"
                                                            data-code="{{ $commission->code }}">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>{{ $commission->insuranceProduct->name ?? '' }}</td>
                                                <td>{{ $commission->insuranceCompany->name ?? '' }}</td>
                                                <td>
                                                    <span class="badge {{ $commission->commission_type === 'fixed' ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $commission->commission }}{{ $commission->commission_type === 'fixed' ? '₹' : '%' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $paymentTypeColors = [
                                                            'agent_full_payment' => 'success',
                                                            'commission_deducted' => 'warning',
                                                            'pay_later_with_adjustment' => 'info',
                                                            'pay_later' => 'secondary',
                                                        ];
                                                        
                                                        $paymentTypeIcons = [
                                                            'agent_full_payment' => 'fas fa-money-bill-wave',
                                                            'commission_deducted' => 'fas fa-hand-holding-usd',
                                                            'pay_later_with_adjustment' => 'fas fa-calendar-alt',
                                                            'pay_later' => 'fas fa-clock',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $paymentTypeColors[$commission->payment_type] ?? 'secondary' }}">
                                                        <i class="{{ $paymentTypeIcons[$commission->payment_type] ?? 'fas fa-money-bill' }} me-1"></i>
                                                        {{ ucwords(str_replace('_', ' ', $commission->payment_type)) }}
                                                    </span>
                                                </td>
                                                <td><span class="badge bg-light text-dark">{{ $commission->gst }}%</span></td>
                                                <td><span class="badge bg-light text-dark">{{ $commission->discount ?? 0 }}%</span></td>
                                                <td><span class="badge bg-dark text-white">{{ $commission->payout ?? 0 }}%</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="javascript:void(0);" class="text-primary edit-commission me-2" 
                                                            data-id="{{ $commission->id }}"
                                                            data-agent="{{ $commission->user_id }}"
                                                            data-product="{{ $commission->insurance_product_id }}"
                                                            data-company="{{ $commission->insurance_company_id }}"
                                                            data-comm-type="{{ $commission->commission_type }}"
                                                            data-comm-value="{{ $commission->commission }}"
                                                            data-payment="{{ $commission->payment_type }}"
                                                            data-gst="{{ $commission->gst }}"
                                                            data-discount="{{ $commission->discount ?? 0 }}"
                                                            data-payout="{{ $commission->payout ?? 0 }}"
                                                            data-settlement="{{ $commission->commission_settlement }}"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="text-danger delete-commission"
                                                            data-id="{{ $commission->id }}" data-bs-toggle="tooltip"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <div class="d-flex justify-content-center mt-4">
                        {{ $agentsWithCommissions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Modal -->
    <div class="modal fade" id="commissionModal" tabindex="-1" aria-labelledby="commissionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  ">
                    <h5 class="modal-title" id="commissionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> <span id="modalTitle">Add New Commission</span>
                    </h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('commission.store') }}" method="POST" id="commissionForm">
                        @csrf
                        <input type="hidden" name="id" id="commissionId" value="">

                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><i class="fas fa-user-tie text-primary me-2"></i> Agent</label>
                                <select class="form-select select2-modal @error('agent_id') is-invalid @enderror"
                                    name="agent_id" id="agentSelect" required>
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-shield-alt text-success me-2"></i> Insurance Product</label>
                                <select class="form-select @error('insurance_product_id') is-invalid @enderror"
                                    name="insurance_product_id" id="productSelect" required>
                                    <option value="">Select Product</option>
                                    @foreach ($insuranceProducts as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('insurance_product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('insurance_product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-building text-success me-2"></i> Insurance Company</label>
                                <select class="form-select @error('insurance_company_id') is-invalid @enderror"
                                    name="insurance_company_id" id="companySelect" required>
                                    <option value="">Select Company</option>
                                    @foreach ($insuranceCompanies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('insurance_company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-credit-card text-info me-2"></i> Payment Type</label>
                                <select class="form-select @error('payment_type') is-invalid @enderror"
                                    name="payment_type" id="paymentType" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="agent_full_payment" {{ old('payment_type') == 'agent_full_payment' ? 'selected' : '' }}>
                                        Agent Full Payment</option>
                                    <option value="commission_deducted" {{ old('payment_type') == 'commission_deducted' ? 'selected' : '' }}>
                                        Commission Deducted</option>
                                    <option value="pay_later_with_adjustment" {{ old('payment_type') == 'pay_later_with_adjustment' ? 'selected' : '' }}>
                                        Pay Later with Adjustment</option>
                                    <option value="pay_later" {{ old('payment_type') == 'pay_later' ? 'selected' : '' }}>
                                        Pay Later</option>
                                </select>
                                @error('payment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-percent text-warning me-2"></i> Commission Type</label>
                                <select class="form-select @error('commission_type') is-invalid @enderror"
                                    name="commission_type" id="commissionType" required>
                                    <option value="">Select Type</option>
                                    <option value="fixed" {{ old('commission_type') == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount (₹)</option>
                                    <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)</option>
                                </select>
                                @error('commission_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-coins text-warning me-2"></i> Commission Value</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('commission') is-invalid @enderror"
                                        name="commission" id="commissionValue" value="{{ old('commission') }}"
                                        required step="0.01">
                                    <span class="input-group-text" id="commissionSymbol">₹/%</span>
                                </div>
                                @error('commission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-receipt text-danger me-2"></i> GST (%)</label>
                                <input type="number" class="form-control @error('gst') is-invalid @enderror"
                                    name="gst" id="gstValue" value="{{ old('gst', 15.25) }}"
                                    required step="0.01">
                                @error('gst')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-tag text-info me-2"></i> Discount</label>
                                <input type="number" class="form-control @error('discount') is-invalid @enderror"
                                    name="discount" id="discountValue" value="{{ old('discount', 0) }}"
                                    step="0.01">
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-hand-holding-usd text-success me-2"></i> Payout</label>
                                <input type="number" class="form-control @error('payout') is-invalid @enderror"
                                    name="payout" id="payoutValue" value="{{ old('payout', 0) }}"
                                    step="0.01">
                                @error('payout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><i class="fas fa-check-circle text-success me-2"></i> Commission Settlement</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('commission_settlement') is-invalid @enderror" 
                                        type="checkbox" name="commission_settlement" id="commissionSettlement" 
                                        value="1" {{ old('commission_settlement') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="commissionSettlement">
                                        Mark as settled (Previous month's commission will be adjusted in next policy premium)
                                    </label>
                                </div>
                                @error('commission_settlement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Discard
                    </button>
                    <button type="button" id="saveCommission" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> <span id="saveButtonText">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Delete Commission</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Are you sure you want to delete this commission code? This action cannot be undone.</p>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" id="confirmDelete" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div class="modal fade" id="multiDeleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Delete Multiple Commissions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Are you sure you want to delete the selected commission codes? This action cannot be undone.</p>
                    <form id="bulkDeleteForm" action="{{ route('commission.bulk-delete') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ids" id="deleteIds">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" id="confirmBulkDelete" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete All Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for agent search
            $('.select2-agent').select2({
                placeholder: "Search agent...",
                allowClear: true,
                minimumInputLength: 0 
            }).on('change', function() {
                $('#filterForm').submit();
            });

            // Initialize Select2 inside modal - Fixed to properly display agent names
            $('.select2-modal').select2({
                dropdownParent: $('#commissionModal'),
                placeholder: "Select agent...",
                width: '100%',
                allowClear: true,
                minimumInputLength: 0
            });

            // Update commission symbol based on type
            $('#commissionType').change(function() {
                const type = $(this).val();
                $('#commissionSymbol').text(type === 'fixed' ? '₹' : '%');
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Copy to clipboard functionality
            $('.copy-code').click(function() {
                const code = $(this).data('code');
                navigator.clipboard.writeText(code).then(() => {
                    toastr.success('Code copied to clipboard');
                });
            });

            // Group checkbox functionality
            $('.group-checkbox').click(function() {
                const group = $(this).closest('.commission-group');
                group.find('.commission-checkbox').prop('checked', this.checked);
                updateBulkActionButtons();
            });

            // Individual checkbox handling
            $(document).on('change', '.commission-checkbox', function() {
                updateBulkActionButtons();
                
                // Update group checkbox if all or none are checked
                const group = $(this).closest('.commission-group');
                const total = group.find('.commission-checkbox').length;
                const checked = group.find('.commission-checkbox:checked').length;
                
                group.find('.group-checkbox').prop('checked', total === checked && total > 0);
            });


            // Update bulk delete button state
            function updateBulkActionButtons() {
                const checkedCount = $('.commission-checkbox:checked').length;
                $('#deleteSelectedBtn').prop('disabled', checkedCount === 0)
                    .html(`<i class="fas fa-trash-alt me-1"></i> Delete Selected (${checkedCount})`);
            }

            // Initialize delete functionality
            $('.delete-commission').click(function() {
                const id = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("commission.delete", "") }}/' + id);
                $('#deleteModal').modal('show');
            });

            // Confirm single delete
            $('#confirmDelete').click(function() {
                $('#deleteForm').submit();
            });

            // Bulk delete functionality
            $('#deleteSelectedBtn').click(function() {
                const ids = $('.commission-checkbox:checked').map((i, el) => el.value).get();
                $('#deleteIds').val(ids.join(','));
                $('#multiDeleteModal').modal('show');
            });

            // Confirm bulk delete
            $('#confirmBulkDelete').click(function() {
                $('#bulkDeleteForm').submit();
            });

            // Save commission form
            $('#saveCommission').click(function() {
                $('#commissionForm').submit();
            });

            // Edit commission
            $('.edit-commission').click(function() {
                const id = $(this).data('id');
                const agentId = $(this).data('agent');
                const productId = $(this).data('product');
                const companyId = $(this).data('company');
                const commType = $(this).data('comm-type');
                const commValue = $(this).data('comm-value');
                const payment = $(this).data('payment');
                const gst = $(this).data('gst');
                const discount = $(this).data('discount');
                const payout = $(this).data('payout');
                const settlement = $(this).data('settlement');
                
                // Set form values
                $('#commissionId').val(id);
                $('#agentSelect').val(agentId).trigger('change');
                $('#productSelect').val(productId);
                $('#companySelect').val(companyId);
                $('#commissionType').val(commType).trigger('change');
                $('#commissionValue').val(commValue);
                $('#paymentType').val(payment);
                $('#gstValue').val(gst);
                $('#discountValue').val(discount);
                $('#payoutValue').val(payout);
                $('#commissionSettlement').prop('checked', settlement == 1);
                
                // Update modal title and button text
                $('#modalTitle').text('Edit Commission');
                $('#saveButtonText').text('Update');
                
                // Show modal
                $('#commissionModal').modal('show');
            });
            
            // Reset form when modal is closed
            $('#commissionModal').on('hidden.bs.modal', function() {
                $('#commissionForm')[0].reset();
                $('#commissionId').val('');
                $('#modalTitle').text('Add New Commission');
                $('#saveButtonText').text('Save');
                $('#agentSelect').val('').trigger('change');
                $('#commissionSettlement').prop('checked', false);
            });
            
            // Fix Select2 rendering in modal when shown
            $('#commissionModal').on('shown.bs.modal', function() {
                $('.select2-modal').select2({
                    dropdownParent: $('#commissionModal'),
                    placeholder: "Select agent...",
                    width: '100%',
                    allowClear: true
                });
            });
            
            // Show modal if there are validation errors
            @if($errors->any())
                $('#commissionModal').modal('show');
            @endif
        });
    </script>
@endpush