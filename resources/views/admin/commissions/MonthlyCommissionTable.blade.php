<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm shadow-hover">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ul text-primary me-2"></i>
                        Monthly Commission Records
                    </h5>
                    <span class="badge bg-primary">{{ $monthlyCommissions->count() }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Month/Year</th>
                                <th>Agent</th>
                                <th>Policies</th>
                                <th>Premium</th>
                                <th>Commission</th>
                                <th>Payout</th>
                               
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyCommissions as $commission)
                                <tr>
                                    <td>
                                        <strong>{{ date('F', mktime(0, 0, 0, $commission->month, 10)) }}</strong>
                                        <div class="small text-muted">{{ $commission->year }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="agent-avatar me-2">
                                                {{ strtoupper(substr($commission->agent->name ?? 'N/A', 0, 1)) }}
                                            </div>
                                            <div>
                                                {{ $commission->agent->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($commission->policies_count) }}</td>
                                    <td>₹{{ number_format($commission->total_premium, 2) }}</td>
                                    <td>₹{{ number_format($commission->total_commission, 2) }}</td>
                                    <td>₹{{ number_format($commission->total_payout, 2) }}</td>
                                  
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-search empty-state-icon"></i>
                                            <h5>No Commission Records Found</h5>
                                            <p class="text-muted">Try adjusting your filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>