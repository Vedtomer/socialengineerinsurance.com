@extends('admin.layouts.customer')

@section('title', 'Analytics Dashboard')



@section('content')
    <!-- Filters Section -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-three">
              
                <div class="widget-content">
                    <form id="filter-form" method="post" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-2">
                            <label for="filter_type" class="form-label">Filter Type</label>
                            <select class="form-select" id="filter_type" name="filter_type" onchange="toggleFilterFields()">
                                <option value="monthly" {{ $data['filter_type'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ $data['filter_type'] == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ $data['filter_type'] == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 monthly-filter" {{ $data['filter_type'] != 'monthly' ? 'style=display:none' : '' }}>
                            <label for="month" class="form-label">Month</label>
                            <select class="form-select" id="month" name="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $data['selected_month'] == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-2 yearly-filter monthly-filter" {{ $data['filter_type'] == 'custom' ? 'style=display:none' : '' }}>
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select" id="year" name="year">
                                @for ($i = now()->year - 5; $i <= now()->year; $i++)
                                    <option value="{{ $i }}" {{ $data['selected_year'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-2 custom-filter" {{ $data['filter_type'] != 'custom' ? 'style=display:none' : '' }}>
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $data['start_date'] }}">
                        </div>
                        
                        <div class="col-md-2 custom-filter" {{ $data['filter_type'] != 'custom' ? 'style=display:none' : '' }}>
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $data['end_date'] }}">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="agent_id" class="form-label">Agent</label>
                            <select class="form-select" id="agent_id" name="agent_id">
                                <option value="">All Agents</option>
                                @foreach ($data['agents'] as $agent)
                                    <option value="{{ $agent->id }}" {{ $data['selected_agent_id'] == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-three">
                <div class="widget-heading">
                    <h5 class="">Policy Trends</h5>
                </div>
                <div class="widget-content">
                    <div id="uniqueVisits" style="height: 360px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row layout-top-spacing">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four h-100">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Total Premium</h6>
                        </div>
                        <div class="task-action">
                            <div class="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-content">
                        <div class="w-info">
                            <p class="value">₹ {{ $data['premiums'] }}</p>
                            <p class="text-muted">Total Premium Generated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four h-100">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Policies Issued</h6>
                        </div>
                        <div class="task-action">
                            <div class="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-content">
                        <div class="w-info">
                            <p class="value">{{ $data['policyCount'] }}</p>
                            <p class="text-muted">Total Number of Policies</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-five h-100">
                <div class="widget-content">
                    <div class="account-box">
                        <div class="info-box">
                            <div class="icon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="balance-info">
                                <h6>Total Payout</h6>
                                <p>₹{{ $data['payout'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-five h-100">
                <div class="widget-content">
                    <div class="account-box">
                        <div class="info-box">
                            <div class="icon">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                            <div class="balance-info">
                                <h6>Payment Due</h6>
                                <p>₹{{ $data['final_amount_due'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insurance Companies -->
    <div class="row layout-top-spacing">
        <div class="col-md-12">
            <h4 class="mb-3">Insurance Companies Performance</h4>
        </div>

        @forelse ($data['companies'] as $company)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-six">
                    <div class="widget-heading">
                        <h6 class="mb-3">
                            {{ $company->name }}
                            <span class="badge badge-light-dark mb-2 me-4">{{ $company->total_policies }} Policies</span>
                        </h6>
                        <div class="task-action">
                            @if($company->image)
                                <img src="{{ $company->image }}" alt="{{ $company->name }}" width="50px" height="50px">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="w-chart my-4">
                        <div class="w-chart-section">
                            <div class="w-detail">
                                <p class="w-title">Premium</p>
                                <p class="w-stats">₹{{ $company->total_premium }}</p>
                            </div>
                        </div>
                        <div class="w-chart-section">
                            <div class="w-detail">
                                <p class="w-title">Payout</p>
                                <p class="w-stats">₹{{ $company->total_payout }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress bar showing premium to payout ratio -->
                    @php
                        $ratio = ($company->total_premium > 0) 
                                ? min(100, round(($company->total_payout / $company->total_premium) * 100)) 
                                : 0;
                    @endphp
                    <div class="progress br-30 mb-2">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $ratio }}%" aria-valuenow="{{ $ratio }}" 
                             aria-valuemin="0" aria-valuemax="100">{{ $ratio }}%</div>
                    </div>
                    <p class="text-muted text-center">Payout to Premium Ratio</p>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No company data available for the selected period.</div>
            </div>
        @endforelse
    </div>

    <!-- Agent Policy Rates Table -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Agent Policy Rates</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <table id="policy-rates-table" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <!-- Dynamic Month Headers -->
                                @if (!empty($data['policyRates']))
                                    @php
                                        $monthLabels = collect($data['policyRates'])->first()['labels'] ?? [];
                                    @endphp
                                    @foreach ($monthLabels as $monthYear)
                                        <th>{{ $monthYear }}</th>
                                    @endforeach
                                @endif
                                <th style="font-weight:bold;width:0px !important">Days Since Last Policy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['policyRates'] as $agentId => $agentData)
                                @if (!empty($agentData['data']))
                                    <tr>
                                        <!-- Agent Name and Total -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <span class="avatar-text rounded-circle bg-primary">
                                                        {{ substr($agentData['agent_name'], 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="bs-tooltip agent-name" data-full-name="{{ $agentData['agent_name'] }}">
                                                        {{ \Illuminate\Support\Str::limit($agentData['agent_name'], 16) }}
                                                    </span>
                                                    <span class="badge badge-light-primary me-2">
                                                        {{ array_sum($agentData['data']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Monthly Data for Each Agent -->
                                        @foreach ($agentData['data'] as $index => $policyCount)
                                            <td>
                                                @if($policyCount > 0)
                                                    <span class="badge {{ $policyCount > 5 ? 'badge-primary' : 'badge-light-primary' }}">
                                                        {{ $policyCount }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ $policyCount }}</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        <!-- Days Since Last Policy -->
                                        <td style="background-color: @php
                                            $days = $agentData['days_since_last_policy'];
                                            if ($days > 180) {
                                                echo '#780000';  // Deep Dark Red
                                            } elseif ($days > 150) {
                                                echo '#8B0000';  // Dark Red
                                            } elseif ($days > 120) {
                                                echo '#AA0000';  // Rich Red
                                            } elseif ($days > 90) {
                                                echo '#CC0000';  // Medium Red
                                            } elseif ($days > 60) {
                                                echo '#E53E3E';  // Bright Red
                                            } elseif ($days > 45) {
                                                echo '#ED6464';  // Light Red
                                            } elseif ($days > 30) {
                                                echo '#F56565';  // Coral Red
                                            } elseif ($days > 21) {
                                                echo '#FC8181';  // Salmon
                                            } elseif ($days > 14) {
                                                echo '#FEB2B2';  // Light Coral
                                            } elseif ($days > 10) {
                                                echo '#FF9933';  // Dark Orange
                                            } elseif ($days > 7) {
                                                echo '#FFB347';  // Medium Orange
                                            } elseif ($days > 5) {
                                                echo '#FFD700';  // Gold
                                            } elseif ($days > 3) {
                                                echo '#9ACD32';  // Yellow Green
                                            } elseif ($days > 2) {
                                                echo '#48BB78';  // Medium Green
                                            } else {
                                                echo '#2F855A';  // Forest Green
                                            }
                                        @endphp; color: @php
                                            echo ($days > 7) ? 'white' : 'black';
                                        @endphp; font-weight: bold; text-align: center;">
                                            {{ $agentData['days_since_last_policy'] }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Chart and Filters -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chart
            let chartData = @json($data['chartData']);
            
            var options = {
                chart: {
                    height: 360,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: chartData.series,
                xaxis: {
                    categories: chartData.categories,
                },
                yaxis: {
                    title: {
                        text: 'Policy Count'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " policies"
                        }
                    }
                },
                colors: ['#4361ee', '#e7515a', '#2196f3']
            }
            
            var chart = new ApexCharts(
                document.querySelector("#uniqueVisits"),
                options
            );
            
            chart.render();
            
            // Handle Filter Type Change
            function toggleFilterFields() {
                const filterType = document.getElementById('filter_type').value;
                
                if (filterType === 'monthly') {
                    document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'block');
                    document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'none');
                } else if (filterType === 'yearly') {
                    document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'block');
                    document.getElementById('month').parentElement.style.display = 'none';
                    document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'none');
                } else if (filterType === 'custom') {
                    document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'block');
                }
            }
            
            document.getElementById('filter_type').addEventListener('change', toggleFilterFields);
            
            // Initialize DataTable
            if (document.getElementById('policy-rates-table')) {
                $('#policy-rates-table').DataTable({
                    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "oLanguage": {
                        "oPaginate": { 
                            "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                            "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                        },
                        "sInfo": "Showing page _PAGE_ of _PAGES_",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Search...",
                        "sLengthMenu": "Results :  _MENU_",
                    },
                    "order": [[ 0, "asc" ]],
                    "stripeClasses": [],
                    "lengthMenu": [7, 10, 20, 50],
                    "pageLength": 10,
                });
            }
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
        
        function toggleFilterFields() {
            const filterType = document.getElementById('filter_type').value;
            
            if (filterType === 'monthly') {
                document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'block');
                document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'none');
            } else if (filterType === 'yearly') {
                document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'block');
                document.getElementById('month').parentElement.style.display = 'none';
                document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'none');
            } else if (filterType === 'custom') {
                document.querySelectorAll('.monthly-filter').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.custom-filter').forEach(el => el.style.display = 'block');
            }
        }
    </script>
@endsection