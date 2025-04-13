@extends('admin.layouts.customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#"> Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users"></i> Agent Management</li>
@endsection

@section('content')
    <div class="row mb-4">
        <!-- Active Agents Card -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-light-success p-3 rounded me-3">
                            <i class="fas fa-user-check text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Active Agents</h6>
                            <h2 class="mb-0">{{ $activeAgentsCount }}</h2>
                        </div>
                    </div>
                    <a href="{{ route('agent.management', ['status' => 1]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        
        <!-- Inactive Agents Card -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-light-danger p-3 rounded me-3">
                            <i class="fas fa-user-times text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Inactive Agents</h6>
                            <h2 class="mb-0">{{ $inactiveAgentsCount }}</h2>
                        </div>
                    </div>
                    <a href="{{ route('agent.management', ['status' => 0]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <!-- Agent Filter Card -->
            <div class="card mb-4 shadow-sm rounded-lg">
                <div class="card-body pb-0">
                    <div class="collapse show" id="filterCollapse">
                        <form id="filterForm" action="{{ route('agent.management') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search text-primary me-2"></i> Search Agent
                                    </label>
                                    <select class="form-select select2-agent" name="agent_id">
                                        <option value="">All Agents</option>
                                        @foreach ($allAgents as $agent)
                                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }} ({{ $agent->mobile_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-filter text-primary me-2"></i> Status Filter
                                    </label>
                                    <select class="form-select" name="status" onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Agent Listing Card -->
            <div class="card shadow-sm rounded-lg">
                <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Agents</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('agent.management', array_merge(request()->except('sort'), ['sort' => request('sort') === 'asc' ? 'desc' : 'asc'])) }}"
                            class="btn btn-light btn-sm me-2">
                            <i class="fas fa-sort-alpha-{{ request('sort') === 'asc' ? 'down' : 'up' }}"></i>
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#agentModal">
                            <i class="fas fa-plus me-1"></i> Add Agent
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($agents->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No Agent records found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 200px;">Name</th>
                                        <th style="width: 150px;">Location</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $index => $agent)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-light-primary p-2 rounded-circle me-2 d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-user-tie text-primary fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium">{{ $agent->name }}</h6>
                                                        <span class="fw-light">{{ $agent->mobile_number }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $agent->city }}{{ $agent->city && $agent->state ? ', ' : '' }}{{ $agent->state }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $agent->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $agent->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" 
                                                       class="text-primary me-2" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit"
                                                       onclick="editAgent({{ $agent->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" 
                                                       class="text-warning me-2" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Change Password"
                                                       onclick="changePassword({{ $agent->id }})">
                                                        <i class="fas fa-key"></i>
                                                    </a>
                                                    <a href="#" 
                                                       class="text-info me-2" 
                                                       data-bs-toggle="tooltip" 
                                                       title="View Codes"
                                                       onclick="window.location.href='{{ route('commission.management', ['agent_id' => $agent->id]) }}'">
                                                        <i class="fas fa-code"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Modal -->
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
                    <form action="{{ route('agent.store') }}" method="POST" id="agentForm">
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

    <!-- Password Change Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">
                        <i class="fas fa-key me-2"></i> Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agent.update.password') }}" method="POST" id="passwordForm">
                        @csrf
                        <input type="hidden" name="agent_id" id="passwordAgentId" value="">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock text-primary me-2"></i> New Password
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-check-circle text-primary me-2"></i> Confirm Password
                            </label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required minlength="8">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" id="savePassword" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Password
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

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Save agent form
            $('#saveAgent').click(function() {
                $('#agentForm').submit();
            });
            
            // Save password form
            $('#savePassword').click(function() {
                $('#passwordForm').submit();
            });
            
            // Show modal if there are validation errors for agent form
            @if($errors->has('name') || $errors->has('mobile_number') || $errors->has('email') || $errors->has('state') || $errors->has('city') || $errors->has('address') || $errors->has('active'))
                $('#agentModal').modal('show');
            @endif
            
            // Show modal if there are validation errors for password form
            @if($errors->has('password') || $errors->has('password_confirmation') || $errors->has('agent_id'))
                $('#passwordModal').modal('show');
            @endif
            
            // Show modal if edit parameter is present
            @if(isset($editAgent))
                editAgent({{ $editAgent->id }});
            @endif
        });
        
        // Edit agent function
        function editAgent(id) {
            // Remove URL parameter without page reload
            if (history.pushState) {
                const newUrl = window.location.href.split('?')[0];
                window.history.pushState({path:newUrl}, '', newUrl);
            }
            
            $.ajax({
                url: "{{ route('agent.get') }}/" + id,
                type: "GET",
                success: function(response) {
                    $('#modalTitle').text('Update Agent');
                    $('#saveButtonText').text('Update');
                    $('#agentId').val(response.id);
                    $('#agentName').val(response.name);
                    $('#mobileNumber').val(response.mobile_number);
                    $('#emailAddress').val(response.email);
                    $('#state').val(response.state);
                    $('#city').val(response.city);
                    $('#address').val(response.address);
                    $('#agentStatus').prop('checked', response.status == 1);
                    $('#agentModal').modal('show');
                }
            });
        }
        
        // Change password function
        function changePassword(id) {
            $('#passwordAgentId').val(id);
            $('#password').val('');
            $('#password_confirmation').val('');
            $('#passwordModal').modal('show');
        }
        
        // Clear modal function when closing or discarding
        function clearModal() {
            $('#modalTitle').text('Add New Agent');
            $('#saveButtonText').text('Save');
            $('#agentForm')[0].reset();
            $('#agentId').val('');
            
            // Remove URL parameter without page reload
            if (history.pushState) {
                const newUrl = window.location.href.split('?')[0];
                window.history.pushState({path:newUrl}, '', newUrl);
            }
        }
    </script>
@endpush