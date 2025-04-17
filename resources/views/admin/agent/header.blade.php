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