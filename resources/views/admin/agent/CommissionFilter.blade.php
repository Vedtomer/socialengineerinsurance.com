<div class="card mb-4 shadow-sm rounded-lg">
    <div class="card-body pb-0">
      

        <div class="collapse show" id="filterCollapse">
            <form id="filterForm" action="{{ route('commission.management') }}" method="GET">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user-tie text-primary me-2"></i> Search Agent
                        </label>
                        <select class="form-select select2-agent" name="agent_id">
                            <option value="">All Agents</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>