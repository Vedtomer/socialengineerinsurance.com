@extends('admin.layouts.customer')

@section('title', 'Analytics Dashboard')



@section('content')

    <style>
        /* Global Dashboard Styles */
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        /* Card Styles */
        .card {
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.03), 0 0 6px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.07), 0 0 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        /* Typography Improvements */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            letter-spacing: -0.02em;
        }

        .text-primary {
            color: #4361ee !important;
        }

        .text-success {
            color: #2ecc71 !important;
        }

        .text-warning {
            color: #f1c40f !important;
        }

        .text-danger {
            color: #e7515a !important;
        }

        .bg-primary {
            background-color: #4361ee !important;
        }

        .bg-success {
            background-color: #2ecc71 !important;
        }

        .bg-warning {
            background-color: #f1c40f !important;
        }

        .bg-danger {
            background-color: #e7515a !important;
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3a56d6;
            border-color: #3a56d6;
        }

        .btn-outline-primary {
            color: #4361ee;
            border-color: #4361ee;
        }

        .btn-outline-primary:hover {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: 6px;
            padding: 0.65rem 1rem;
            border-color: #e0e6ed;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        /* Tables */
        .table {
            --bs-table-hover-bg: rgba(67, 97, 238, 0.05);
        }

        .table thead th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #e0e6ed;
        }

        .table tbody tr {
            vertical-align: middle;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        .badge-light-primary {
            background-color: rgba(67, 97, 238, 0.15);
            color: #4361ee;
        }

        .badge-light-success {
            background-color: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
        }

        .badge-light-warning {
            background-color: rgba(241, 196, 15, 0.15);
            color: #f1c40f;
        }

        .badge-light-danger {
            background-color: rgba(231, 81, 90, 0.15);
            color: #e7515a;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .modal-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* Avatar Styles */
        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            color: white;
        }

        .avatar-text {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .container-fluid,
            .container {
                padding-left: 20px;
                padding-right: 20px;
            }

            .layout-spacing {
                padding: 12px 0;
            }

            h5.card-title {
                font-size: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- Filters Section -->
    <!-- Filter Modal Button -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-filter me-2"></i>Dashboard Analytics</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-sliders-h me-2"></i>Filter Data
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="filterModalLabel">
                        <i class="fas fa-filter text-primary me-2"></i>Filter Dashboard Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filter-form" method="post" action="{{ route('admin.dashboard') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="filter_type" class="form-label">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Filter Type
                            </label>
                            <select class="form-select" id="filter_type" name="filter_type" onchange="toggleFilterFields()">
                                <option value="monthly" {{ $data['filter_type'] == 'monthly' ? 'selected' : '' }}>
                                    <i class="fas fa-calendar-day"></i> Monthly
                                </option>
                                <option value="yearly" {{ $data['filter_type'] == 'yearly' ? 'selected' : '' }}>
                                    <i class="fas fa-calendar-year"></i> Yearly
                                </option>
                                <option value="custom" {{ $data['filter_type'] == 'custom' ? 'selected' : '' }}>
                                    <i class="fas fa-calendar-check"></i> Custom Date Range
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 monthly-filter"
                            {{ $data['filter_type'] != 'monthly' ? 'style=display:none' : '' }}>
                            <label for="month" class="form-label">
                                <i class="fas fa-calendar-day text-primary me-2"></i>Month
                            </label>
                            <select class="form-select" id="month" name="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ $data['selected_month'] == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-6 yearly-filter monthly-filter"
                            {{ $data['filter_type'] == 'custom' ? 'style=display:none' : '' }}>
                            <label for="year" class="form-label">
                                <i class="fas fa-calendar-year text-primary me-2"></i>Year
                            </label>
                            <select class="form-select" id="year" name="year">
                                @for ($i = now()->year - 5; $i <= now()->year; $i++)
                                    <option value="{{ $i }}"
                                        {{ $data['selected_year'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-6 custom-filter"
                            {{ $data['filter_type'] != 'custom' ? 'style=display:none' : '' }}>
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-minus text-primary me-2"></i>Start Date
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ $data['start_date'] }}">
                        </div>

                        <div class="col-md-6 custom-filter"
                            {{ $data['filter_type'] != 'custom' ? 'style=display:none' : '' }}>
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-plus text-primary me-2"></i>End Date
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ $data['end_date'] }}">
                        </div>

                        <div class="col-md-6">
                            <label for="agent_id" class="form-label">
                                <i class="fas fa-user-tie text-primary me-2"></i>Agent
                            </label>
                            <select class="form-select" id="agent_id" name="agent_id">
                                <option value=""><i class="fas fa-users"></i> All Agents</option>
                                @foreach ($data['agents'] as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ $data['selected_agent_id'] == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-chart-line me-2"></i>Policy Trends
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" id="viewByMonth">Monthly
                                View</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="viewByQuarter">Quarterly
                                View</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="uniqueVisits" style="height: 370px;"></div>
                </div>
            </div>
        </div>
    </div>


    <!-- Stats Cards -->
    <div class="row layout-top-spacing">
        <!-- Total Premium Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="card border-0 shadow-sm h-100 bg-soft-primary">
                <div class="card-body position-relative p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-1 text-primary fw-bold">Total Premium</h6>
                            <h3 class="mb-0 fw-bold">₹{{ $data['premiums'] }}</h3>
                            <p class="text-muted mb-0 mt-2">Total Premium Generated</p>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar avatar-lg rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-rupee-sign fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-wave position-absolute bottom-0 start-0 w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="rgba(66, 135, 245, 0.1)" fill-opacity="1"
                                d="M0,192L48,176C96,160,192,128,288,133.3C384,139,480,181,576,202.7C672,224,768,224,864,197.3C960,171,1056,117,1152,112C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Policies Issued Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="card border-0 shadow-sm h-100 bg-soft-success">
                <div class="card-body position-relative p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-1 text-success fw-bold">Policies Issued</h6>
                            <h3 class="mb-0 fw-bold">{{ $data['policyCount'] }}</h3>
                            <p class="text-muted mb-0 mt-2">Total Number of Policies</p>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar avatar-lg rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-file-contract fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-wave position-absolute bottom-0 start-0 w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="rgba(45, 193, 125, 0.1)" fill-opacity="1"
                                d="M0,96L48,128C96,160,192,224,288,240C384,256,480,224,576,213.3C672,203,768,213,864,202.7C960,192,1056,160,1152,165.3C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payout Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="card border-0 shadow-sm h-100 bg-soft-warning">
                <div class="card-body position-relative p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-1 text-warning fw-bold">Total Payout</h6>
                            <h3 class="mb-0 fw-bold">₹{{ $data['payout'] }}</h3>
                            <p class="text-muted mb-0 mt-2">Total Payouts Made</p>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar avatar-lg rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-hand-holding-usd fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-wave position-absolute bottom-0 start-0 w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="rgba(255, 186, 43, 0.1)" fill-opacity="1"
                                d="M0,224L48,213.3C96,203,192,181,288,154.7C384,128,480,96,576,106.7C672,117,768,171,864,197.3C960,224,1056,224,1152,202.7C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Due Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="card border-0 shadow-sm h-100 bg-soft-danger">
                <div class="card-body position-relative p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-1 text-danger fw-bold">Payment Due</h6>
                            <h3 class="mb-0 fw-bold">₹{{ number_format($data['final_amount_due'], 0) }}</h3>
                            <p class="text-muted mb-0 mt-2">Outstanding Payments</p>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar avatar-lg rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fas fa-credit-card fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-wave position-absolute bottom-0 start-0 w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="rgba(231, 81, 90, 0.1)" fill-opacity="1"
                                d="M0,160L48,149.3C96,139,192,117,288,133.3C384,149,480,203,576,208C672,213,768,171,864,144C960,117,1056,107,1152,128C1248,149,1344,203,1392,229.3L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this CSS to your stylesheet -->
    <style>
        .bg-soft-primary {
            background-color: rgba(66, 135, 245, 0.07) !important;
        }

        .bg-soft-success {
            background-color: rgba(45, 193, 125, 0.07) !important;
        }

        .bg-soft-warning {
            background-color: rgba(255, 186, 43, 0.07) !important;
        }

        .bg-soft-danger {
            background-color: rgba(231, 81, 90, 0.07) !important;
        }

        .stat-icon {
            position: relative;
            z-index: 1;
        }

        .stat-wave {
            height: 50px;
            overflow: hidden;
            z-index: 0;
        }
    </style>

    <div class="row layout-top-spacing">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-building me-2"></i>Insurance Companies Performance
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ count($data['companies']) }} Companies</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row" id="companiesContainer">
                @forelse ($data['companies'] as $index => $company)
                    <div
                        class="col-xl-{{ count($data['companies']) <= 3 ? '4' : (count($data['companies']) == 4 ? '6' : '4') }} col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                        <div class="card border-0 shadow-sm h-100 company-card">
                            <div class="card-header bg-gradient-light border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold text-dark">{{ $company->name }}</h5>
                                    <div class="company-logo">
                                        @if ($company->image)
                                            <img src="{{ $company->image }}" alt="{{ $company->name }}"
                                                class="rounded-circle" width="50px" height="50px">
                                        @else
                                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-building text-primary fa-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light bg-opacity-25">
                                <div class="text-center mb-4">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                        <i class="fas fa-file-contract me-1"></i> {{ $company->total_policies }} Policies
                                    </span>
                                </div>

                                <div class="row g-0 text-center mb-4">
                                    <div class="col-6 border-end">
                                        <div class="p-3">
                                            <h6 class="text-muted mb-1">
                                                <i class="fas fa-money-bill-wave me-1 text-success"></i> Premium
                                            </h6>
                                            <h4 class="mb-0 fw-bold text-success">₹{{ $company->total_premium }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3">
                                            <h6 class="text-muted mb-1">
                                                <i class="fas fa-hand-holding-dollar me-1 text-danger"></i> Payout
                                            </h6>
                                            <h4 class="mb-0 fw-bold text-danger">₹{{ $company->total_payout }}</h4>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $ratio =
                                        $company->total_premium > 0
                                            ? min(100, round(($company->total_payout / $company->total_premium) * 100))
                                            : 0;

                                    // Determine progress bar color based on ratio
                                    $progressColor = 'bg-success';
                                    $textColor = 'text-success';
                                    if ($ratio > 50 && $ratio <= 75) {
                                        $progressColor = 'bg-warning';
                                        $textColor = 'text-warning';
                                    } elseif ($ratio > 75) {
                                        $progressColor = 'bg-danger';
                                        $textColor = 'text-danger';
                                    }
                                @endphp

                                <div class="px-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted small">Payout to Premium Ratio</span>
                                        <span class="fw-bold {{ $textColor }}">{{ $ratio }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 10px;">
                                        <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                            style="width: {{ $ratio }}%;" aria-valuenow="{{ $ratio }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 py-3">
                                <div class="text-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-chart-pie me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (count($data['companies']) % 2 == 1 && $index == 0 && count($data['companies']) > 3)
                        <!-- Force break after first item when odd number of items and more than 3 -->
            </div>
            <div class="row">
                @endif

                @if (count($data['companies']) == 5 && $index == 2)
                    <!-- Force break after 3rd item when 5 items total -->
            </div>
            <div class="row">
                @endif
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle me-2"></i> No company data available for the selected period.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        /* Add hover effects for company cards */
        .company-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .company-card .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Responsive font sizes for smaller screens */
        @media (max-width: 768px) {
            .company-card h4 {
                font-size: 1.2rem;
            }

            .company-card h6 {
                font-size: 0.85rem;
            }
        }
    </style>



    <!-- Agent Policy Rates Table -->
    <!-- Agent Policy Rates Table -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-user-chart me-2"></i>Agent Policy Performance
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" id="toggleInactiveAgents">
                            <i class="fas fa-eye-slash me-1"></i>Hide Inactive Agents
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="policy-rates-table" class="table dt-table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Agent</th>
                                    <!-- Dynamic Month Headers -->
                                    @if (!empty($data['policyRates']))
                                        @php
                                            $monthLabels = collect($data['policyRates'])->first()['labels'] ?? [];
                                        @endphp
                                        @foreach ($monthLabels as $monthYear)
                                            <th class="text-center fw-bold">{{ $monthYear }}</th>
                                        @endforeach
                                    @endif
                                    <th class="text-center fw-bold" style="min-width: 80px;">Days Since<br>Last Policy
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['policyRates'] as $agentId => $agentData)
                                    @if (!empty($agentData['data']))
                                        <tr
                                            class="agent-row {{ $agentData['days_since_last_policy'] > 60 ? 'inactive-agent' : '' }}">
                                            <!-- Agent Name and Total -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2">
                                                        @php
                                                            // Generate background color based on agent name
                                                            $colors = [
                                                                'primary',
                                                                'success',
                                                                'warning',
                                                                'danger',
                                                                'info',
                                                                'secondary',
                                                            ];
                                                            $colorIndex =
                                                                crc32($agentData['agent_name']) % count($colors);
                                                            $bgColor = $colors[$colorIndex];
                                                        @endphp
                                                        <span class="avatar-text rounded-circle bg-{{ $bgColor }}">
                                                            {{ substr($agentData['agent_name'], 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold agent-name" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ $agentData['agent_name'] }}">
                                                            {{ \Illuminate\Support\Str::limit($agentData['agent_name'], 16) }}
                                                        </span>
                                                        <div class="mt-1">
                                                            <span class="badge bg-{{ $bgColor }} rounded-pill">
                                                                <i class="fas fa-file-signature me-1"></i>
                                                                {{ array_sum($agentData['data']) }}
                                                            </span>

                                                            @php
                                                                $avgPolicies =
                                                                    array_sum($agentData['data']) /
                                                                    count($agentData['data']);
                                                                $trend = 'flat';
                                                                if (count($agentData['data']) >= 3) {
                                                                    $recent = array_slice($agentData['data'], -3);
                                                                    $older = array_slice($agentData['data'], -6, 3);

                                                                    $recentAvg = array_sum($recent) / count($recent);
                                                                    $olderAvg = array_sum($older) / count($older);

                                                                    if ($recentAvg > $olderAvg * 1.2) {
                                                                        $trend = 'up';
                                                                    } elseif ($recentAvg < $olderAvg * 0.8) {
                                                                        $trend = 'down';
                                                                    }
                                                                }

                                                                $trendIcon = '';
                                                                $trendColor = '';

                                                                if ($trend === 'up') {
                                                                    $trendIcon = 'fa-chart-line';
                                                                    $trendColor = 'text-success';
                                                                } elseif ($trend === 'down') {
                                                                    $trendIcon = 'fa-chart-line-down';
                                                                    $trendColor = 'text-danger';
                                                                } else {
                                                                    $trendIcon = 'fa-arrows-alt-h';
                                                                    $trendColor = 'text-warning';
                                                                }
                                                            @endphp

                                                            <span class="ms-2 {{ $trendColor }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Performance Trend">
                                                                <i class="fas {{ $trendIcon }}"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Monthly Data for Each Agent -->
                                            @foreach ($agentData['data'] as $index => $policyCount)
                                                <td class="text-center">
                                                    @if ($policyCount > 0)
                                                        <span
                                                            class="badge {{ $policyCount > 5 ? 'bg-primary' : 'bg-light-primary text-primary' }} policy-badge">
                                                            {{ $policyCount }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <!-- Days Since Last Policy -->
                                            @php
                                                $days = $agentData['days_since_last_policy'];
                                                $bgColor = '#2F855A'; // Default: Green
                                                $textColor = 'black';

                                                if ($days > 180) {
                                                    $bgColor = '#780000'; // Deep Dark Red
                                                    $textColor = 'white';
                                                } elseif ($days > 150) {
                                                    $bgColor = '#8B0000'; // Dark Red
                                                    $textColor = 'white';
                                                } elseif ($days > 120) {
                                                    $bgColor = '#AA0000'; // Rich Red
                                                    $textColor = 'white';
                                                } elseif ($days > 90) {
                                                    $bgColor = '#CC0000'; // Medium Red
                                                    $textColor = 'white';
                                                } elseif ($days > 60) {
                                                    $bgColor = '#E53E3E'; // Bright Red
                                                    $textColor = 'white';
                                                } elseif ($days > 45) {
                                                    $bgColor = '#ED6464'; // Light Red
                                                    $textColor = 'white';
                                                } elseif ($days > 30) {
                                                    $bgColor = '#F56565'; // Coral Red
                                                    $textColor = 'white';
                                                } elseif ($days > 21) {
                                                    $bgColor = '#FC8181'; // Salmon
                                                    $textColor = 'white';
                                                } elseif ($days > 14) {
                                                    $bgColor = '#FEB2B2'; // Light Coral
                                                    $textColor = 'black';
                                                } elseif ($days > 10) {
                                                    $bgColor = '#FF9933'; // Dark Orange
                                                    $textColor = 'black';
                                                } elseif ($days > 7) {
                                                    $bgColor = '#FFB347'; // Medium Orange
                                                    $textColor = 'black';
                                                } elseif ($days > 5) {
                                                    $bgColor = '#FFD700'; // Gold
                                                    $textColor = 'black';
                                                } elseif ($days > 3) {
                                                    $bgColor = '#9ACD32'; // Yellow Green
                                                    $textColor = 'black';
                                                } elseif ($days > 2) {
                                                    $bgColor = '#48BB78'; // Medium Green
                                                    $textColor = 'black';
                                                }
                                            @endphp

                                            <td class="text-center">
                                                <div class="days-badge"
                                                    style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                    {{ $days }}
                                                    @if ($days < 3)
                                                        <i class="fas fa-check-circle ms-1"></i>
                                                    @elseif($days > 60)
                                                        <i class="fas fa-exclamation-circle ms-1"></i>
                                                    @endif
                                                </div>
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
    </div>

    <style>
        .policy-badge {
            min-width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .bg-light-primary {
            background-color: rgba(67, 97, 238, 0.15);
        }

        .days-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 30px;
            border-radius: 15px;
            font-weight: bold;
        }

        .agent-row.inactive-agent {
            opacity: 0.7;
        }
    </style>

    <!-- JavaScript for Chart and Filters -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chart
            let chartData = @json($data['chartData']);

            var options = {
                chart: {
                    height: 370,
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    },
                    events: {
                        dataPointMouseEnter: function(event, chartContext, config) {
                            const dataPointIndex = config.dataPointIndex;
                            const seriesIndex = config.seriesIndex;
                            const value = config.w.config.series[seriesIndex].data[dataPointIndex];

                            // Show value at top of bar
                            const seriesName = config.w.config.series[seriesIndex].name;
                            const category = config.w.config.xaxis.categories[dataPointIndex];

                            const tooltip = document.getElementById('customTooltip');
                            if (!tooltip) {
                                const newTooltip = document.createElement('div');
                                newTooltip.id = 'customTooltip';
                                newTooltip.style.position = 'absolute';
                                newTooltip.style.backgroundColor = 'rgba(0,0,0,0.7)';
                                newTooltip.style.color = '#fff';
                                newTooltip.style.padding = '5px 10px';
                                newTooltip.style.borderRadius = '3px';
                                newTooltip.style.fontSize = '12px';
                                newTooltip.style.zIndex = '99';
                                document.body.appendChild(newTooltip);
                            }

                            const tooltip2 = document.getElementById('customTooltip');
                            tooltip2.innerHTML = `<strong>${seriesName}</strong>: ${value} policies`;
                            tooltip2.style.left = `${event.clientX}px`;
                            tooltip2.style.top = `${event.clientY - 40}px`;
                            tooltip2.style.display = 'block';
                        },
                        dataPointMouseLeave: function() {
                            const tooltip = document.getElementById('customTooltip');
                            if (tooltip) {
                                tooltip.style.display = 'none';
                            }
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top'
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val;
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: chartData.series,
                xaxis: {
                    categories: chartData.categories,
                    labels: {
                        style: {
                            fontSize: '12px'
                        },
                        rotateAlways: false,
                        hideOverlappingLabels: true
                    },
                    title: {
                        text: 'Time Period'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Policy Count'
                    },
                    min: 0,
                    max: Math.max(...chartData.series.map(s => Math.max(...s.data))) * 1.2 || 10,
                    forceNiceScale: true,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.1,
                        gradientToColors: undefined,
                        inverseColors: false,
                        opacityFrom: 0.85,
                        opacityTo: 0.95,
                        stops: [0, 100]
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " policies"
                        }
                    },
                    theme: 'dark',
                    x: {
                        show: true
                    }
                },
                colors: ['#4361ee', '#2ecc71', '#f1c40f'],
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    offsetY: 0,
                    fontSize: '13px',
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 50
                    }
                },
                grid: {
                    borderColor: '#e0e6ed',
                    strokeDashArray: 5,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 10
                    }
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#uniqueVisits"),
                options
            );

            chart.render();

            // Add switch between monthly and quarterly views
            document.getElementById('viewByMonth').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('viewByQuarter').classList.remove('active');

                // Update chart with original monthly data
                chart.updateOptions({
                    xaxis: {
                        categories: chartData.categories
                    }
                });
                chart.updateSeries(chartData.series);
            });

            document.getElementById('viewByQuarter').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('viewByMonth').classList.remove('active');

                // Calculate quarterly data - this is just an example
                // You would need to properly aggregate your data by quarters
                const quarterlyCategories = ['Q1', 'Q2', 'Q3', 'Q4'];

                // Example of transforming data to quarterly view
                // This is simplified - you'd need to correctly aggregate your actual data
                const quarterlyData = chartData.series.map(series => {
                    return {
                        name: series.name,
                        data: [
                            series.data.slice(0, 3).reduce((a, b) => a + b, 0),
                            series.data.slice(3, 6).reduce((a, b) => a + b, 0),
                            series.data.slice(6, 9).reduce((a, b) => a + b, 0),
                            series.data.slice(9, 12).reduce((a, b) => a + b, 0)
                        ]
                    };
                });

                chart.updateOptions({
                    xaxis: {
                        categories: quarterlyCategories
                    }
                });
                chart.updateSeries(quarterlyData);
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const companiesContainer = document.getElementById('companiesContainer');
            const companies = companiesContainer.querySelectorAll('.company-card').length;

            // This script will help ensure proper distribution of cards based on screen size and count
            function adjustCompanyLayout() {
                const screenWidth = window.innerWidth;
                const companyCards = document.querySelectorAll('.company-card');

                if (screenWidth < 768) {
                    // For mobile, always full width
                    companyCards.forEach(card => {
                        card.parentElement.className = card.parentElement.className.replace(/col-xl-\d+/g,
                            'col-xl-12');
                    });
                } else {
                    if (companies === 1) {
                        companyCards[0].parentElement.className = companyCards[0].parentElement.className.replace(
                            /col-xl-\d+/g, 'col-xl-12');
                    } else if (companies === 2) {
                        companyCards.forEach(card => {
                            card.parentElement.className = card.parentElement.className.replace(
                                /col-xl-\d+/g, 'col-xl-6');
                        });
                    } else if (companies === 3) {
                        companyCards.forEach(card => {
                            card.parentElement.className = card.parentElement.className.replace(
                                /col-xl-\d+/g, 'col-xl-4');
                        });
                    } else if (companies === 4) {
                        companyCards.forEach(card => {
                            card.parentElement.className = card.parentElement.className.replace(
                                /col-xl-\d+/g, 'col-xl-6');
                        });
                    } else {
                        // For 5 or more companies
                        companyCards.forEach((card, index) => {
                            if (companies === 5 && (index === 3 || index === 4)) {
                                card.parentElement.className = card.parentElement.className.replace(
                                    /col-xl-\d+/g, 'col-xl-6');
                            } else {
                                card.parentElement.className = card.parentElement.className.replace(
                                    /col-xl-\d+/g, 'col-xl-4');
                            }
                        });
                    }
                }
            }

            // Initial layout adjustment
            adjustCompanyLayout();

            // Adjust layout on window resize
            window.addEventListener('resize', adjustCompanyLayout);
        });
    </script>
@endsection
