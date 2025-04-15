<div class="card shadow-sm filter-card mb-4">
    <div class="card-body">
        <form id="filterForm" action="{{ route('monthly-commissions') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="year" class="form-label"><i class="fas fa-calendar-year me-1"></i> Year</label>
                <select name="year" id="year" class="form-select auto-submit">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="month" class="form-label"><i class="fas fa-calendar-day me-1"></i> Month</label>
                <select name="month" id="month" class="form-select auto-submit">
                    <option value="all" {{ $month == 'all' ? 'selected' : '' }}>All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="agent_id" class="form-label"><i class="fas fa-user-tie me-1"></i> Agent</label>
                <select name="agent_id" id="agent_id" class="form-select auto-submit">
                    <option value="">All Agents</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ $agentId == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>


