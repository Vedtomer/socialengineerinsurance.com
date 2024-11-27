@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">WhatsApp</a></li>
    <li class="breadcrumb-item active" aria-current="page">Message Logs</li>
@endsection

@section('content')
  
                <div class="card">
                    {{-- <div class="card-header">
                        <form action="{{ route('WhatsappMessageLog') }}" method="GET" class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label">Select Date</label>
                                <input type="date" 
                                       name="date" 
                                       class="form-control" 
                                       value="{{ $selectedDate ?? now()->format('Y-m-d') }}"
                                       max="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div> --}}

            <!-- Filter Section -->
            <div class="card-body collapse" id="filterSection">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Message Type</label>
                        <select class="form-select" name="message_type">
                            <option value="">All Types</option>
                            <option value="daily_report">Daily Report</option>
                            <option value="no_policy">No Policy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="1">Successful</option>
                            <option value="0">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Mobile Number</th>
                                <th>Message Type</th>
                                <th>Policy Count</th>
                                <th>Total Commission</th>
                                <th>Day</th>
                                <th>Sent At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messageLogs as $log)
                                <tr class="{{ $log->is_successful ? 'table-success' : 'table-danger' }}">
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td>{{ $log->mobile_number }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                    {{ $log->message_type == 'daily_report' ? 'bg-primary' : 'bg-warning' }}">
                                            {{ ucfirst($log->message_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->policy_count ?? 'N/A' }}</td>
                                    <td>₹ {{ number_format($log->total_commission ?? 0, 2) }}</td>
                                    <td>{{ $log->days_since_last_policy ?? 'N/A' }}</td>
                                    <td>{{ $log->sent_at ? $log->sent_at->format('h:i A') : 'Not Sent' }}</td>

                                    <td>
                                        @if ($log->is_successful)
                                            <span class="badge bg-success">Successful</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#logDetailsModal{{ $log->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-info">
                                            No WhatsApp message logs found.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $messageLogs->firstItem() }} to {{ $messageLogs->lastItem() }}
                            of {{ $messageLogs->total() }} entries
                        </div>
                        {{ $messageLogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals for Log Details -->
        @foreach ($messageLogs as $log)
            <div class="modal fade" id="logDetailsModal{{ $log->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Message Log Details - #{{ $log->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>User Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> {{ $log->user->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Mobile:</strong> {{ $log->mobile_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Message Details</h6>
                                    <p class="mb-1"><strong>Type:</strong> {{ ucfirst($log->message_type) }}</p>
                                    <p class="mb-1"><strong>Sent At:</strong>
                                        {{ $log->sent_at ? $log->sent_at->format('d M Y H:i:s') : 'Not Sent' }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Policy Information</h6>
                                    <p class="mb-1"><strong>Policy Count:</strong> {{ $log->policy_count ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Total Commission:</strong> ₹
                                        {{ number_format($log->total_commission ?? 0, 2) }}</p>
                                    <p class="mb-1"><strong>Days Since Last Policy:</strong>
                                        {{ $log->days_since_last_policy ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Status</h6>
                                    @if ($log->is_successful)
                                        <span class="badge bg-success">Successful</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </div>
                            </div>

                            @if ($log->request_payload)
                                <hr>
                                <h6>Request Payload</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode($log->request_payload, JSON_PRETTY_PRINT) }}</pre>
                            @endif

                            @if ($log->response_body)
                                <hr>
                                <h6>Response Body</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode($log->response_body, JSON_PRETTY_PRINT) }}</pre>
                            @endif

                            @if ($log->error_message)
                                <hr>
                                <h6 class="text-danger">Error Message</h6>
                                <pre class="text-danger bg-light p-3 rounded">{{ $log->error_message }}</pre>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter toggle
            const filterToggle = document.getElementById('filterToggle');
            const filterSection = document.getElementById('filterSection');

            filterToggle.addEventListener('click', function() {
                filterSection.classList.toggle('show');
            });

            // Filter form submission
            const filterForm = document.getElementById('filterForm');
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Implement AJAX filtering logic here
                console.log('Filter submitted');
            });
        });
    </script>
@endsection
