@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Agent</a></li>
    <li class="breadcrumb-item active" aria-current="page">Policy List</li>
@endsection

@section('content')
<!-- Analytics Dashboard Section -->
<div class="row mb-4">
    <!-- Overall Policy Statistics -->
    <div class="col-12 mb-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4 class="mb-0">Policy Overview</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-bg bg-primary text-white p-3 rounded me-3">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Policies</h6>
                                        <h3 class="mb-0">{{ $analytics['total_policies'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-bg bg-success text-white p-3 rounded me-3">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Two Wheeler</h6>
                                        <h3 class="mb-0">{{ $analytics['total_two_wheeler'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($analytics['total_two_wheeler'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-bg bg-warning text-white p-3 rounded me-3">
                                        <i class="fas fa-rickshaw"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">E-Rickshaw</h6>
                                        <h3 class="mb-0">{{ $analytics['total_e_rickshaw'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-warning" style="width: {{ ($analytics['total_e_rickshaw'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-bg bg-info text-white p-3 rounded me-3">
                                        <i class="fas fa-rupee-sign"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Premium</h6>
                                        <h3 class="mb-0">₹{{ $analytics['total_premium'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-info" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="col-12 mb-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4 class="mb-0">Financial Summary</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Premium</h6>
                                <h3 class="text-primary mb-0">₹{{ $analytics['total_premium'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Commission</h6>
                                <h3 class="text-success mb-0">₹{{ $analytics['total_commission'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Net Amount</h6>
                                <h3 class="text-info mb-0">₹{{ $analytics['total_net_amount'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Payout</h6>
                                <h3 class="text-warning mb-0">₹{{ $analytics['total_payout'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods - Main Focus -->
    <div class="col-12 mb-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4 class="mb-0">Payment Methods</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row">
                    @foreach($analytics['payment_methods'] as $key => $method)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="text-capitalize">{{ str_replace('_', ' ', $key) }}</h5>
                                    <span class="badge bg-primary">{{ $method['count'] }} Policies</span>
                                </div>
                                <p class="text-muted small mb-3">{{ $method['description'] }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Premium Amount:</h6>
                                    <h4 class="mb-0 text-primary">₹{{ $method['amount'] }}</h4>
                                </div>
                                <div class="progress mt-3" style="height: 5px;">
                                    <div class="progress-bar bg-primary" style="width: {{ ($method['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Company & Agent Stats in Tabs -->
    <div class="col-12 mb-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4 class="mb-0">Detailed Analytics</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <ul class="nav nav-tabs mb-3" id="analytics-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" type="button" role="tab" aria-controls="companies" aria-selected="true">
                            Insurance Companies
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="agents-tab" data-bs-toggle="tab" data-bs-target="#agents" type="button" role="tab" aria-controls="agents" aria-selected="false">
                            Agent Performance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trends-tab" data-bs-toggle="tab" data-bs-target="#trends" type="button" role="tab" aria-controls="trends" aria-selected="false">
                            Monthly Trends
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="analytics-tab-content">
                    <!-- Companies Tab -->
                    <div class="tab-pane fade show active" id="companies" role="tabpanel" aria-labelledby="companies-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Policies</th>
                                        <th>Premium Amount</th>
                                        <th>Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['company_distribution'] as $company)
                                    <tr>
                                        <td>{{ $company['company_name'] }}</td>
                                        <td>{{ $company['count'] }}</td>
                                        <td>₹{{ number_format($company['premium'], 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ ($company['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Agents Tab -->
                    <div class="tab-pane fade" id="agents" role="tabpanel" aria-labelledby="agents-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Agent</th>
                                        <th>Policies</th>
                                        <th>Premium Amount</th>
                                        <th>Commission</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['agent_performance'] as $agent)
                                    <tr>
                                        <td>{{ $agent['agent_name'] }}</td>
                                        <td>{{ $agent['count'] }}</td>
                                        <td>₹{{ number_format($agent['premium'], 2) }}</td>
                                        <td>₹{{ number_format($agent['commission'], 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: {{ ($agent['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Monthly Trends Tab -->
                    <div class="tab-pane fade" id="trends" role="tabpanel" aria-labelledby="trends-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Policies</th>
                                        <th>Premium Amount</th>
                                        <th>Commission</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['monthly_trend'] as $month => $trend)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($month)) }}</td>
                                        <td>{{ $trend['count'] }}</td>
                                        <td>₹{{ number_format($trend['premium'], 2) }}</td>
                                        <td>₹{{ number_format($trend['commission'], 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: {{ ($trend['count'] / $analytics['total_policies']) * 100 }}%"></div>
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
        </div>
    </div>
</div>

<!-- Original Policy List Table -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Policy No.</th>
                            <th>Policy Type</th>
                            <th>Customer Name</th>
                            <th>Policy Date</th>
                            <th>Net Amount</th>
                            <th>GST</th>
                            <th>Premium</th>
                            <th>Commission</th>
                            <th>Upload Policy</th>
                            <th>Agent</th>
                            <th>Insurance Company</th>
                            <th>Payment By</th>
                            <th>Discount</th>
                            <th>Payout</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->policy_no }}</td>
                            <td>
                                @if($user->policy_type == NULL)
                                <span>E-Rickshaw</span>
                                @else
                                <span>{{ $user->policy_type }}</span>
                                @endif
                            </td>
                            <td>{{ $user->customername }}</td>
                            <td> {{ date('M d, Y', strtotime($user->policy_start_date)) }} </td>
                            <td>{{ $user->net_amount }}</td>
                            <td>{{ $user->gst }}</td>
                            <td>{{ $user->premium }}</td>
                            <td>{{ $user->agent_commission }}</td>
                            <td>
                                @if (empty($user->policy_link))
                                    <form action="{{ route('updateagentid', ['royalsundaram_id' => $user->id]) }}"
                                        method="post" enctype="multipart/form-data" onchange="submitForm(this)">
                                        @csrf
                                        <input type="file" name="policy_file">
                                    </form>
                                @else
                                    <a href="{{ $user->policy_link }}" download="{{ $user->policy_link }}"><i
                                            class="fa fa-download"> Download</i></a>
                                @endif
                            </td>
                            <td>
                                @if (optional($user->agent)->name)
                                    {{ $user->agent->name }}
                                @else
                                    <select class="form-select js-example-basic-single select2"
                                        data-control="select2" data-placeholder="Select an option"
                                        onchange="confirmAgentChange(this); location = this.value;">
                                        <option value="" selected disabled>Select Agent</option>
                                        @foreach ($agentData as $record)
                                            <option
                                                value="{{ route('updateagentid', ['agent_id' => $record->id, 'royalsundaram_id' => $user->id]) }}">
                                                {{ $record->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                            <td>{{ $user->Company->name }}</td>
                            <td>{{ $user->payment_by }}</td>
                            <td>{{ $user->discount }}</td>
                            <td>{{ $user->payout }}</td>
                            <td>
                                <button class="btn btn-danger" onclick="policyDelete('{{$user->id}}')" >Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function policyDelete(id) {
        Swal.fire({
            title: "Please confirm to Delete",
            text: "Do you want to proceed?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Proceed",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                var token = '{{ csrf_token() }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                $.post('/admin/policy-list/delete/' + id)
                    .done(function(response) {
                        location.reload();
                    })
                    .fail(function(error) {
                        console.error(error);
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred while processing your request.",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 4000
                        });
                    });
            }
        });
    }

    // Submit policy file upload form when file selected
    function submitForm(form) {
        form.submit();
    }

    // Function to handle agent change confirmation
    function confirmAgentChange(select) {
        Swal.fire({
            title: "Change Agent",
            text: "Are you sure you want to change the agent?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, Change",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (!result.isConfirmed) {
                select.selectedIndex = 0;
            }
        });
    }

    // Initialize tabs if not already handled by your framework
    document.addEventListener('DOMContentLoaded', function() {
        const triggerTabList = [].slice.call(document.querySelectorAll('#analytics-tabs button'));
        triggerTabList.forEach(function(triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
    });
</script>
@endsection