  <!-- Dashboard Header -->
  <div class="row mb-4 align-items-center">
    <div class="col-md-5">
        <h1 class="h2 mb-1">
            <i class="fa-solid fa-chart-line text-primary me-2"></i> Monthly Commissions Dashboard
        </h1>
        <p class="text-muted">Track, analyze and manage agent performance and commission payouts</p>
    </div>
    <div class="col-md-7">
        <div class="card border-0 bg-light shadow-sm">
            <div class="card-body p-3">
                <form id="filterForm" action="{{ route('monthly-commissions') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-calendar-year text-primary"></i></span>
                            <select name="year" id="year" class="form-select auto-submit">
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-calendar-days text-info"></i></span>
                            <select name="month" id="month" class="form-select auto-submit">
                                <option value="all" {{ $month == 'all' ? 'selected' : '' }}>All Months</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-user-tie text-success"></i></span>
                            <select name="agent_id" id="agent_id" class="form-select auto-submit">
                                <option value="">All Agents</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $agentId == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPI Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Total Premium Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-scale position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-uppercase text-muted mb-1 small"><i class="fa-solid fa-wallet text-primary me-1"></i> Total Premium</h6>
                        <h3 class="mb-1 display-10 fw-bold">₹{{ number_format($totals->total_premium ?? 0) }}</h3>
                        @if(isset($previousMonthData) && isset($currentMonthData) && $previousMonthData->total_premium > 0)
                        @php $percentChange = (($currentMonthData->total_premium - $previousMonthData->total_premium)/$previousMonthData->total_premium)*100 @endphp
                        <div class="d-inline-flex align-items-center small {{ $percentChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa-solid {{ $percentChange >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-1"></i>
                            {{ number_format(abs($percentChange), 1) }}% since last month
                        </div>
                        @endif
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary-light">
                            <i class="fa-solid fa-indian-rupee-sign fa-xl text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Total Commission & Payout Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 hover-scale position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-uppercase text-muted mb-1 small"><i class="fa-solid fa-hand-holding-dollar text-success me-1"></i> Commission & Payout</h6>
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <div>
                                <span class="small text-muted">Commission</span>
                                <h4 class="mb-0">₹{{ number_format($totals->total_commission ?? 0) }}</h4>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <span class="small text-muted">Payout</span>
                                <h4 class="mb-0">₹{{ number_format($totals->total_payout ?? 0) }}</h4>
                            </div>
                        </div>
                        @if(isset($previousMonthData) && isset($currentMonthData) && $previousMonthData->total_commission > 0)
                        @php $percentChange = (($currentMonthData->total_commission - $previousMonthData->total_commission)/$previousMonthData->total_commission)*100 @endphp
                        <div class="d-inline-flex align-items-center small {{ $percentChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa-solid {{ $percentChange >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-1"></i>
                            {{ number_format(abs($percentChange)) }}% commission growth
                        </div>
                        @endif
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-success-light">
                            <i class="fa-solid fa-credit-card fa-xl text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Total Policies & Agents Card -->
    <div class="col-lg-4 col-md-12">
        <div class="card border-0 shadow-sm h-100 hover-scale position-relative overflow-hidden">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-uppercase text-muted mb-1 small"><i class="fa-solid fa-chart-simple text-warning me-1"></i> Performance Metrics</h6>
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <div>
                                <span class="small text-muted">Policies</span>
                                <h4 class="mb-0">{{ number_format($totals->policies_count ?? 0) }}</h4>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div>
                                <span class="small text-muted">Active Agents</span>
                                <h4 class="mb-0">{{ number_format($totals->agent_count ?? 0) }}</h4>
                            </div>
                        </div>
                        @if(isset($previousMonthData) && isset($currentMonthData) && $previousMonthData->policies_count > 0)
                        @php $percentChange = (($currentMonthData->policies_count - $previousMonthData->policies_count)/$previousMonthData->policies_count)*100 @endphp
                        <div class="d-inline-flex align-items-center small {{ $percentChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa-solid {{ $percentChange >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-1"></i>
                            {{ number_format(abs($percentChange), 1) }}% policy growth
                        </div>
                        @endif
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-warning-light">
                            <i class="fa-solid fa-file-contract fa-xl text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>