@extends('admin.layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Reports Dashboard</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy-report" type="button" role="tab" aria-controls="policy-report" aria-selected="true">Policy Reports</button>
                        </li>
                        <!-- Add more report tabs as needed -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="future-tab" data-bs-toggle="tab" data-bs-target="#future-report" type="button" role="tab" aria-controls="future-report" aria-selected="false">Other Reports</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="reportTabsContent">
                        <!-- Policy Report Tab -->
                        <div class="tab-pane fade show active" id="policy-report" role="tabpanel" aria-labelledby="policy-tab">
                            <form action="{{ route('reports.policy.download') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="company_id" class="form-label">Company</label>
                                        <select class="form-select" id="company_id" name="company_id">
                                            <option value="">All Companies</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="agent_id" class="form-label">Agent</label>
                                        <select class="form-select" id="agent_id" name="agent_id">
                                            <option value="">All Agents</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="policy_type" class="form-label">Policy Type</label>
                                        <select class="form-select" id="policy_type" name="policy_type">
                                            <option value="">All Types</option>
                                            @foreach($insuranceProducts as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="payment_by" class="form-label">Payment Type</label>
                                        <select class="form-select" id="payment_by" name="payment_by">
                                            <option value="">All Payment Types</option>
                                            @foreach($paymentTypes as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-download"></i> Download Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Future Report Tab (placeholder) -->
                        <div class="tab-pane fade" id="future-report" role="tabpanel" aria-labelledby="future-tab">
                            <div class="alert alert-info">
                                <p>Additional report types will be added here in the future.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add client-side validation or dynamic filtering
        
        // Example: Set default date range to current month
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        // Format dates as YYYY-MM-DD
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        document.getElementById('from_date').value = formatDate(firstDay);
        document.getElementById('to_date').value = formatDate(today);
    });
</script>
@endsection