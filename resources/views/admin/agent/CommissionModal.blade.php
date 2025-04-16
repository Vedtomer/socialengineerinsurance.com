<div class="modal fade" id="commissionModal" tabindex="-1" aria-labelledby="commissionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header  ">
                <h5 class="modal-title" id="commissionModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> <span id="modalTitle">Add New Agent Code</span>
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
                        
                        {{-- <div class="col-md-12">
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
                        </div> --}}
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