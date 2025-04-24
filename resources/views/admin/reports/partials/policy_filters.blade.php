<form action="{{ route('reports.policy.download') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-3">
            <div class="form-group">
                <label for="from_date" class="form-label">
                    <i class="fas fa-calendar-alt me-1"></i> From Date
                </label>
                <input type="date" class="form-control" id="from_date" name="from_date">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="to_date" class="form-label">
                    <i class="fas fa-calendar-alt me-1"></i> To Date
                </label>
                <input type="date" class="form-control" id="to_date" name="to_date">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="company_id" class="form-label">
                    <i class="fas fa-building me-1"></i> Insurance Company
                </label>
                <select class="form-select select2" id="company_id" name="company_id">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="report_period" class="form-label">
                    <i class="fas fa-chart-line me-1"></i> Report Period
                </label>
                <select class="form-select" id="report_period" name="report_period">
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row g-3 mt-2">
        <div class="col-md-4">
            <div class="form-group">
                <label for="agent_id" class="form-label">
                    <i class="fas fa-user-tie me-1"></i> Agent
                </label>
                <select class="form-select select2" id="agent_id" name="agent_id">
                    <option value="">All Agents</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="policy_type" class="form-label">
                    <i class="fas fa-file-contract me-1"></i> Policy Type
                </label>
                <select class="form-select select2" id="policy_type" name="policy_type">
                    <option value="">All Types</option>
                    @foreach($insuranceProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="payment_by" class="form-label">
                    <i class="fas fa-money-bill-wave me-1"></i> Payment Type
                </label>
                <select class="form-select select2" id="payment_by" name="payment_by">
                    <option value="">All Payment Types</option>
                    @foreach($paymentTypes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <button type="reset" class="btn btn-outline-secondary me-2">
            <i class="fas fa-undo me-1"></i> Reset Filters
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-file-excel me-1"></i> Download Excel
        </button>
    </div>
</form>