<div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agentModalLabel">
                    <i class="fas fa-user-plus me-2"></i> <span id="modalTitle">Add New Agent</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModal()"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('agent.store') }}" method="POST" id="agentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="agentId" value="">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-user text-primary me-2"></i> Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" id="agentName" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-mobile-alt text-primary me-2"></i> Mobile Number</label>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror"
                                name="mobile_number" id="mobileNumber" value="{{ old('mobile_number') }}"
                                required pattern="[0-9]{10}" title="Please enter 10 digit mobile number">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-envelope text-primary me-2"></i> Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" id="emailAddress" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-id-card text-primary me-2"></i> PAN Number</label>
                            <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                                name="pan_number" id="panNumber" value="{{ old('pan_number') }}"
                                pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Please enter valid PAN number (e.g., ABCDE1234F)">
                            @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-map-marker-alt text-success me-2"></i> State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror"
                                name="state" id="state" value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-city text-success me-2"></i> City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                name="city" id="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-home text-success me-2"></i> Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                name="address" id="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-image text-success me-2"></i> PAN Card Image</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('pan_image') is-invalid @enderror" 
                                    name="pan_image" id="panImage" 
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                <label class="input-group-text" for="panImage">Upload</label>
                            </div>
                            <div class="form-text">Allowed formats: JPG, JPEG, PNG, GIF, WEBP (Max: 2MB)</div>
                            @error('pan_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div id="panImagePreviewContainer" class="mt-2 d-none">
                                <div class="position-relative d-inline-block">
                                    <img id="panImagePreview" src="" class="img-thumbnail" style="max-height: 100px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                        id="removePanImage" title="Remove Image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="remove_pan_image" id="removePanImageFlag" value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="fas fa-toggle-on text-success me-2"></i> Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('active') is-invalid @enderror" 
                                    type="checkbox" name="active" id="agentStatus" 
                                    value="1" checked>
                                <label class="form-check-label" for="agentStatus">
                                    Active
                                </label>
                            </div>
                            @error('active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
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
                <button class="btn btn-light-dark" data-bs-dismiss="modal" onclick="clearModal()">
                    <i class="fas fa-times me-1"></i> Discard
                </button>
                <button type="button" id="saveAgent" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> <span id="saveButtonText">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>