<form action="{{ route('reports.account.download') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Date Range -->
        <div class="col-md-6 mb-3">
            <div class="card border-light">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Date Range</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="account_from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="account_from_date" name="from_date">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="account_to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="account_to_date" name="to_date">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month/Year Selection -->
        <div class="col-md-6 mb-3">
            <div class="card border-light">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>Month/Year Selection</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="account_month" class="form-label">Month</label>
                            <select class="form-select" id="account_month" name="month">
                                <option value="">All Months</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="account_year" class="form-label">Year</label>
                            <select class="form-select" id="account_year" name="year">
                                <option value="">All Years</option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = 2020;
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Selection -->
        <!-- Agent Selection -->
        <div class="col-md-6 mb-3">
            <div class="card border-light">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Agent Selection</h6>
                </div>
                <div class="card-body py-2">
                    <div class="mb-2 w-100">
                        <label for="account_agent_id" class="form-label">Select Agent</label>
                        <select class="form-select select2 w-100" id="account_agent_id" name="agent_id"
                            style="width: 100% !important;">
                            <option value="">All Agents</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="col-12 text-end">
            <button type="reset" class="btn btn-secondary me-2">
                <i class="fas fa-undo-alt me-1"></i>Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download me-1"></i>Download Report
            </button>
        </div>
    </div>
</form>
