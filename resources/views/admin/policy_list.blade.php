@extends('admin.layouts.app')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Agent</a></li>
            <li class="breadcrumb-item active" aria-current="page">Policy List</li>
        </ol>
    </nav>
@endsection
@section('styles')
<!-- Font Awesome 5 CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

@section('content')
<!-- Analytics Dashboard Section -->
<div class="row g-4">
    <!-- Overview Section -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <i class="fas fa-chart-pie text-primary me-2"></i>
                    Policy Overview
                </h5>
            </div>
            <div class="card-body pb-4">
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                                        <i class="fas fa-file-contract fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Total Policies</p>
                                        <h3 class="fw-bold mb-0">{{ $analytics['total_policies'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress mt-auto" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success text-white p-3 rounded-3 me-3">
                                        <i class="fas fa-motorcycle fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Two Wheeler</p>
                                        <h3 class="fw-bold mb-0">{{ $analytics['total_two_wheeler'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress mt-auto" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($analytics['total_two_wheeler'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                                        <i class="fas fa-truck fa-lg"></i>
                                        
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">E-Rickshaw</p>
                                        <h3 class="fw-bold mb-0">{{ $analytics['total_e_rickshaw'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress mt-auto" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: {{ ($analytics['total_e_rickshaw'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info text-white p-3 rounded-3 me-3">
                                        <i class="fas fa-rupee-sign fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Total Premium</p>
                                        <h3 class="fw-bold mb-0">₹{{ $analytics['total_premium'] }}</h3>
                                    </div>
                                </div>
                                <div class="progress mt-auto" style="height: 6px;">
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
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <i class="fas fa-wallet text-success me-2"></i>
                    Premium & Tax
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Total Premium</p>
                        <h4 class="text-primary mb-0 fw-bold">₹{{ ($analytics['total_premium']) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-file-invoice-dollar text-primary"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Total NET Amount</p>
                        <h5 class="text-success mb-0">₹{{ ($analytics['total_net_amount']) }}</h5>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-money-bill-wave text-success"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 small">Total GST</p>
                        <h5 class="text-info mb-0">₹{{ ($analytics['total_gst']) }}</h5>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-percent text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Commission Summary -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <i class="fas fa-hand-holding-usd text-warning me-2"></i>
                    Commission Details
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Total Commission</p>
                        <h5 class="mb-0">₹{{ ($analytics['total_commission']) }}</h5>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-coins text-warning"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Adjusted Commission</p>
                        <h6 class="mb-0">₹{{ ($analytics['total_commission_deducted']) }}</h6>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-calculator text-danger"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Commission Adjust Later</p>
                        <h6 class="mb-0">₹{{ ($analytics['total_commission_will_adjustment']) }}</h6>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-clock text-secondary"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 small">Net Commission Payable</p>
                        <h5 class="text-primary fw-bold mb-0">₹{{ ($analytics['Net_Commission_Payable_Agent']) }}</h5>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-check-circle text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Due Amount -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <i class="fas fa-hand-holding-usd text-danger me-2"></i>
                    Market Due Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <p class="text-muted mb-0 small">Total Due Amount</p>
                        <h4 class="text-danger mb-0 fw-bold">₹{{ ($analytics['total_amount_due_agents']) }}</h4>
                        <p class="text-muted small mt-1 fst-italic">(Amount to be collected from market)</p>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-funnel-dollar text-danger"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 small">Total Due Paid</p>
                        <h5 class="text-success mb-0">₹{{ ($analytics['total_amount_paid_agents']) }}</h5>
                        <p class="text-muted small mt-1 fst-italic">(Amount collected from market)</p>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-check-double text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <i class="fas fa-credit-card text-primary me-2"></i>
                    Payment Methods
                </h5>
            </div>
            <div class="card-body pb-4">
                <div class="row g-4">
                    @foreach($analytics['payment_methods'] as $key => $method)
                    <div class="col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-capitalize mb-0">
                                        {{ \App\Models\Policy::getPaymentTypes()[$key] }}
                                    </h5>
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        {{ $method['count'] }} Policies
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-4">{{ $method['description'] }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-muted">Premium:</h6>
                                    <h5 class="mb-0 text-primary fw-bold">₹{{ ($method['amount']) }}</h5>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-muted">Commission:</h6>
                                    <h5 class="mb-0 text-success">₹{{ ($method['commission']) }}</h5>
                                </div>
                                
                                <div class="progress mt-4" style="height: 8px;">
                                    <div class="progress-bar bg-primary rounded" style="width: {{ ($method['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                </div>
                            </div>
                            <!-- Decorative side color strip -->
                            <div class="position-absolute top-0 start-0 h-100 bg-primary" style="width: 6px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics with Tabs -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white pt-4 pb-0 border-0">
                <h5 class="card-title mb-3 d-flex align-items-center">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Detailed Analytics
                </h5>
                
                <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="analytics-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-3" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" type="button" role="tab" aria-controls="companies" aria-selected="true">
                            <i class="fas fa-building me-2"></i>Insurance Companies
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-3" id="agents-tab" data-bs-toggle="tab" data-bs-target="#agents" type="button" role="tab" aria-controls="agents" aria-selected="false">
                            <i class="fas fa-user-tie me-2"></i>Agent Performance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-3" id="trends-tab" data-bs-toggle="tab" data-bs-target="#trends" type="button" role="tab" aria-controls="trends" aria-selected="false">
                            <i class="fas fa-chart-bar me-2"></i>Monthly Trends
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="analytics-tab-content">
                    <!-- Companies Tab -->
                    <div class="tab-pane fade show active" id="companies" role="tabpanel" aria-labelledby="companies-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Company</th>
                                        <th class="border-0">Policies</th>
                                        <th class="border-0">Premium Amount</th>
                                        <th class="border-0" style="width: 30%">Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['company_distribution'] as $company)
                                    <tr>
                                        <td class="fw-medium">{{ $company['company_name'] }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">{{ $company['count'] }}</span>
                                        </td>
                                        <td class="fw-bold">₹{{ ($company['premium']) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-success rounded" style="width: {{ ($company['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                                </div>
                                                <span class="text-muted small">{{ round(($company['count'] / $analytics['total_policies']) * 100) }}%</span>
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
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Agent</th>
                                        <th class="border-0">Policies</th>
                                        <th class="border-0">Premium</th>
                                        <th class="border-0">Commission</th>
                                        <th class="border-0">Amount Due</th>
                                        <th class="border-0">Amount Paid</th>
                                        <th class="border-0" style="width: 15%">Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['agent_performance'] as $agent)
                                    <tr>
                                        <td class="fw-medium">{{ $agent['agent_name'] }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">{{ $agent['count'] }}</span>
                                        </td>
                                        <td>₹{{ ($agent['premium']) }}</td>
                                        <td class="text-success">₹{{ ($agent['commission']) }}</td>
                                        <td>
                                            {{ (!empty($agent['amount_due']) && $agent['amount_due'] != 0) ? '₹' . ($agent['amount_due']) : '-' }}
                                        </td>
                                        <td>
                                            {{ (!empty($agent['amount_paid']) && $agent['amount_paid'] != 0) ? '₹' . ($agent['amount_paid']) : '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-primary rounded" style="width: {{ ($agent['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                                </div>
                                                <span class="text-muted small">{{ round(($agent['count'] / $analytics['total_policies']) * 100) }}%</span>
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
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Month</th>
                                        <th class="border-0">Policies</th>
                                        <th class="border-0">Premium</th>
                                        <th class="border-0">Commission</th>
                                        <th class="border-0">Due</th>
                                        <th class="border-0">Paid</th>
                                        <th class="border-0" style="width: 15%">Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['monthly_trend'] as $month => $trend)
                                    <tr>
                                        <td class="fw-medium">{{ date('F Y', strtotime($month)) }}</td>
                                        <td>
                                            <span class="badge bg-info rounded-pill">{{ $trend['count'] }}</span>
                                        </td>
                                        <td>₹{{ ($trend['premium']) }}</td>
                                        <td class="text-success">₹{{ ($trend['commission']) }}</td>
                                        <td>
                                            {{ (!empty($trend['amount_due']) && $trend['amount_due'] != 0) ? '₹' . ($trend['amount_due']) : '-' }}
                                        </td>
                                        <td>
                                            {{ (!empty($trend['amount_paid']) && $trend['amount_paid'] != 0) ? '₹' . ($trend['amount_paid']) : '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-info rounded" style="width: {{ ($trend['count'] / $analytics['total_policies']) * 100 }}%"></div>
                                                </div>
                                                <span class="text-muted small">{{ round(($trend['count'] / $analytics['total_policies']) * 100) }}%</span>
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

    <!-- Policy List Table -->
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
                    <table id="html5-extension" class="table table-hover dt-table-hover align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Policy No.</th>
                                <th>Customer Name</th>
                                <th>Policy Date</th>
                                <th>Agent</th>
                                <th>Payment By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $user->policy_no }}</span>
                                        @if (!empty($user->policy_link))
                                        <a href="{{ $user->policy_link }}" download="{{ $user->policy_link }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td title="{{ $user->customername }}">{{ \Illuminate\Support\Str::limit($user->customername, 16) }}</td>
                                <td>{{ date('M d, Y', strtotime($user->policy_start_date)) }}</td>
                                <td>
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
                                                <option value="{{ route('updateagentid', ['agent_id' => $record->id, 'royalsundaram_id' => $user->id]) }}">
                                                    {{ Str::limit($record->name, 16) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ \App\Models\Policy::getPaymentTypes()[$user->payment_by] ?? $user->payment_by }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-primary view-details me-2" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" 
                                           data-policy="{{ $user->policy_no }}"
                                           data-type="{{ $user->policy_type ?? 'E-Rickshaw' }}"
                                           data-customer="{{ $user->customername }}"
                                           data-date="{{ date('M d, Y', strtotime($user->policy_start_date)) }}"
                                           data-net="{{ $user->net_amount }}"
                                           data-gst="{{ $user->gst }}"
                                           data-premium="{{ $user->premium }}"
                                           data-commission="{{ $user->agent_commission }}"
                                           data-agent="{{ optional($user->agent)->name }}"
                                           data-company="{{ $user->Company->name }}"
                                           data-payment="{{ $user->payment_by }}"
                                           data-discount="{{ $user->discount }}"
                                           data-payout="{{ $user->payout }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-outline-danger delete-policy" onclick="policyDelete('{{$user->id}}')">
                                            <i class="fas fa-trash-alt"></i>
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
</div>



<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewDetailsModalLabel">Policy Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Policy No:</strong> <span id="modal-policy-no"></span></p>
              <p><strong>Policy Type:</strong> <span id="modal-policy-type"></span></p>
              <p><strong>Customer Name:</strong> <span id="modal-customer-name"></span></p>
              <p><strong>Policy Date:</strong> <span id="modal-policy-date"></span></p>
              <p><strong>Net Amount:</strong> <span id="modal-net-amount"></span></p>
              <p><strong>GST:</strong> <span id="modal-gst"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Premium:</strong> <span id="modal-premium"></span></p>
              <p><strong>Commission:</strong> <span id="modal-commission"></span></p>
              <p><strong>Agent:</strong> <span id="modal-agent"></span></p>
              <p><strong>Insurance Company:</strong> <span id="modal-company"></span></p>
              <p><strong>Payment By:</strong> <span id="modal-payment"></span></p>
              <p><strong>Discount:</strong> <span id="modal-discount"></span></p>
              <p><strong>Payout:</strong> <span id="modal-payout"></span></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup click handler for view details buttons
        const viewButtons = document.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data from data attributes
                const policy = this.getAttribute('data-policy');
                const type = this.getAttribute('data-type');
                const customer = this.getAttribute('data-customer');
                const date = this.getAttribute('data-date');
                const net = this.getAttribute('data-net');
                const gst = this.getAttribute('data-gst');
                const premium = this.getAttribute('data-premium');
                const commission = this.getAttribute('data-commission');
                const agent = this.getAttribute('data-agent');
                const company = this.getAttribute('data-company');
                const payment = this.getAttribute('data-payment');
                const discount = this.getAttribute('data-discount');
                const payout = this.getAttribute('data-payout');
                
                // Set modal content
                document.getElementById('modal-policy-no').textContent = policy;
                document.getElementById('modal-policy-type').textContent = type;
                document.getElementById('modal-customer-name').textContent = customer;
                document.getElementById('modal-policy-date').textContent = date;
                document.getElementById('modal-net-amount').textContent = net;
                document.getElementById('modal-gst').textContent = gst;
                document.getElementById('modal-premium').textContent = premium;
                document.getElementById('modal-commission').textContent = commission;
                document.getElementById('modal-agent').textContent = agent;
                document.getElementById('modal-company').textContent = company;
                document.getElementById('modal-payment').textContent = payment;
                document.getElementById('modal-discount').textContent = discount;
                document.getElementById('modal-payout').textContent = payout;
            });
        });
    });
    
    // // Function for policy deletion
   
     </script>

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