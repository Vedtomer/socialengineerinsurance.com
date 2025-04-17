@extends('admin.layouts.customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#"> Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users"></i> Agent Management</li>
@endsection

@section('content')
    @include('admin.agent.header')

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
                                            <option value="{{ $agent->id }}"
                                                {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
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
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Agent Listing Card -->
            <div class="card shadow-sm rounded-lg">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Agents</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('agent.management', array_merge(request()->except('sort'), ['sort' => request('sort') === 'asc' ? 'desc' : 'asc'])) }}"
                            class="btn btn-light btn-sm me-2">
                            <i class="fas fa-sort-alpha-{{ request('sort') === 'asc' ? 'down' : 'up' }}"></i>
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#agentModal">
                            <i class="fas fa-plus me-1"></i> Add Agent
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($agents->isEmpty())
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
                                        <th style="width: 120px;">PAN Info</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="max-width: 50px;">
                                            Commission <br>
                                            <small class="text-muted">Settlement Previous Month</small>
                                        </th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $index => $agent)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar bg-light-primary p-2 rounded-circle me-2 d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-user-tie text-primary fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium" data-bs-toggle="tooltip"
                                                            title="{{ $agent->name }}">
                                                            {{ \Illuminate\Support\Str::limit($agent->name, 20) }}
                                                        </h6>
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
                                                @if ($agent->pan_number)
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="badge bg-light-info text-dark">{{ $agent->pan_number }}</span>
                                                        @if ($agent->pan_image)
                                                            <a href="javascript:void(0);" class="ms-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#panImageModal{{ $agent->id }}">
                                                                <i class="fas fa-image text-info" data-bs-toggle="tooltip"
                                                                    title="View PAN Image"></i>
                                                            </a>

                                                            <!-- PAN Image Modal -->
                                                            <div class="modal fade" id="panImageModal{{ $agent->id }}"
                                                                tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">PAN Card -
                                                                                {{ $agent->name }}</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{ asset('storage/pan_images/' . $agent->pan_image) }}"
                                                                                class="img-fluid" alt="PAN Card">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Not Added</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $agent->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $agent->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($agent->commission_settlement)
                                                    <span class="badge " data-bs-toggle="tooltip"
                                                        title="Commission Settlement Enabled">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    </span>
                                                @else
                                                    <span class="badge " data-bs-toggle="tooltip"
                                                        title="Commission Settlement Not Enabled">
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" class="text-primary me-2"
                                                        data-bs-toggle="tooltip" title="Edit"
                                                        onclick="editAgent({{ $agent->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="text-warning me-2"
                                                        data-bs-toggle="tooltip" title="Change Password"
                                                        onclick="changePassword({{ $agent->id }})">
                                                        <i class="fas fa-key"></i>
                                                    </a>
                                                    <a href="#" class="text-info me-2" data-bs-toggle="tooltip"
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
    @include('admin.agent.AgentModal')

    <!-- Password Change Modal -->
    @include('admin.agent.PasswordChangeModal')
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
            @if (
                $errors->has('name') ||
                    $errors->has('mobile_number') ||
                    $errors->has('email') ||
                    $errors->has('state') ||
                    $errors->has('city') ||
                    $errors->has('address') ||
                    $errors->has('active') ||
                    $errors->has('commission_settlement') ||
                    $errors->has('pan_number') ||
                    $errors->has('pan_image'))
                $('#agentModal').modal('show');
            @endif

            // Show modal if there are validation errors for password form
            @if ($errors->has('password') || $errors->has('password_confirmation') || $errors->has('agent_id'))
                $('#passwordModal').modal('show');
            @endif

            // Show modal if edit parameter is present
            @if (isset($editAgent))
                editAgent({{ $editAgent->id }});
            @endif

            // PAN Image preview
            $('#panImage').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#panImagePreview').attr('src', e.target.result);
                        $('#panImagePreviewContainer').removeClass('d-none');
                        $('#removePanImageFlag').val('0');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Remove PAN Image
            $('#removePanImage').click(function() {
                $('#panImage').val('');
                $('#panImagePreviewContainer').addClass('d-none');
                $('#removePanImageFlag').val('1');
            });
        });

        // Edit agent function
        function editAgent(id) {
            // Remove URL parameter without page reload
            if (history.pushState) {
                const newUrl = window.location.href.split('?')[0];
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);
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
                    $('#panNumber').val(response.pan_number || '');
                    $('#state').val(response.state);
                    $('#city').val(response.city);
                    $('#address').val(response.address);
                    $('#agentStatus').prop('checked', response.status == 1);
                    $('#commissionSettlement').prop('checked', response.commission_settlement == 1);

                    // Handle PAN image preview if exists
                    if (response.pan_image) {
                        const imagePath = "{{ asset('storage/pan_images') }}/" + response.pan_image;
                        $('#panImagePreview').attr('src', imagePath);
                        $('#panImagePreviewContainer').removeClass('d-none');
                    } else {
                        $('#panImagePreviewContainer').addClass('d-none');
                    }

                    $('#removePanImageFlag').val('0');
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
            $('#panImagePreviewContainer').addClass('d-none');
            $('#removePanImageFlag').val('0');

            // Remove URL parameter without page reload
            if (history.pushState) {
                const newUrl = window.location.href.split('?')[0];
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);
            }
        }
    </script>
@endpush
