@extends('admin.layouts.customer')



@section('content')
    <div class="container-fluid p-4">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Mobile App Activity Dashboard</h3>
            <div>
                <a class="btn btn-sm btn-soft-primary rounded-pill me-2" href="{{route('admin.app-activity')}}" id="refreshData">
                    <i class="feather feather-refresh-cw me-1"></i> Reset Data
                </a>
                <button class="btn btn-sm btn-primary rounded-pill" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                    <i class="feather feather-sliders me-1"></i> Filters
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        @include("admin.app-activity.modal")
        


        <!-- Activity Overview Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Activity Overview</h5>
                    
                </div>
            </div>

            <!-- Activity Table -->
            <div class="card-body">
                <div class="table-responsive rounded">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4">User</th>
                                <th class="py-3">User Type</th>
                                <th class="py-3">Last Active</th>
                                <th class="py-3 text-end">Activity Count</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activitySummary as $summary)
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                @if ($summary->user_type == 'agent')
                                                    <span class="avatar-title text-white rounded-circle" style="background-color: #8cc445">
                                                        {{ substr($summary->user_name ?? 'A', 0, 1) }}
                                                    </span>
                                                @else
                                                    <span class="avatar-title bg-primary text-white rounded-circle" style="background-color: #061e70">
                                                        {{ substr($summary->user_name ?? 'C', 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium">{{ $summary->user_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $summary->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($summary->user_type == 'agent')
                                            <span class="badge bg-soft-success text-success">Agent</span>
                                        @else
                                            <span class="badge bg-soft-primary text-primary">Customer</span>
                                        @endif
                                    </td>
                                    <td>{{ $summary->last_active ? \Carbon\Carbon::parse($summary->last_active)->diffForHumans() : 'N/A' }}
                                    </td>

                                    <td class="text-end">
                                        <span class="badge bg-soft-primary rounded-pill px-3 py-2">
                                            {{ $summary->activity_count }}
                                        </span>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="mb-3">
                                                <i class="feather feather-activity text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h5 class="text-muted">No activity data available</h5>
                                            <p class="text-muted small mb-3">Change your filters or try again later</p>
                                            
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

    <style>
        /* Enhanced styling */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .rounded-4 {
            border-radius: 0.75rem !important;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-soft-light {
            background-color: rgba(248, 249, 250, 0.5);
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            font-weight: 600;
            font-size: 14px;
        }

        .feather {
            vertical-align: middle;
            width: 24px;
            height: 24px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .empty-state {
            padding: 2rem;
            text-align: center;
        }

        .sparkline-container {
            height: 25px;
            width: 100px;
            display: inline-block;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .bg-soft-light {
                background-color: rgba(33, 37, 41, 0.5);
            }
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            /* Light Blue */
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            /* Light Green */
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            /* Light Cyan */
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
            /* Light Yellow */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate sparklines (would use actual sparkline library in production)
            document.querySelectorAll('.sparkline-container').forEach(function(container) {
                const trendData = JSON.parse(container.dataset.trend);
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('width', '100');
                svg.setAttribute('height', '25');
                svg.setAttribute('viewBox', '0 0 100 25');

                // Create the sparkline
                let pathData = '';
                const maxVal = Math.max(...trendData);

                trendData.forEach((val, index) => {
                    const x = (index / (trendData.length - 1)) * 100;
                    const y = 25 - ((val / maxVal) * 20);

                    if (index === 0) {
                        pathData += `M${x},${y} `;
                    } else {
                        pathData += `L${x},${y} `;
                    }
                });

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', pathData);
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', '#0d6efd');
                path.setAttribute('stroke-width', '2');

                // Add dot for last value
                const lastX = 100;
                const lastY = 25 - ((trendData[trendData.length - 1] / maxVal) * 20);
                const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                dot.setAttribute('cx', lastX);
                dot.setAttribute('cy', lastY);
                dot.setAttribute('r', '3');
                dot.setAttribute('fill', '#0d6efd');

                svg.appendChild(path);
                svg.appendChild(dot);
                container.appendChild(svg);
            });

            // Initialize tooltips and popovers if Bootstrap JS is loaded
            if (typeof bootstrap !== 'undefined') {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

                const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
                popovers.forEach(popover => new bootstrap.Popover(popover));
            }
        });
    </script>
@endsection
