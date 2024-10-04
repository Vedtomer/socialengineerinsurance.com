@extends('admin.layouts.app')

@section('title', 'Agent Policy Rates')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Policy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Agent Policy </li>
@endsection

@section('content')
    <div class="row layout-top-spacing">
        <div class="col-lg-6 mx-auto mt-4">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Combined Monthly Policy Count</h5>
                    <canvas id="combinedChart" height="100"></canvas>
                </div>
            </div>
        </div>


            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Agent</th>

                                    <!-- Dynamic Month Headers -->
                                    @if (!empty($policyRates))
                                        @php
                                            // Extract month labels from the first agent's data (assuming all agents have the same months)
$monthLabels = collect($policyRates)->first()['labels'] ?? [];
                                        @endphp
                                        @foreach ($monthLabels as $monthYear)
                                            <th>{{ date('M', mktime(0, 0, 0, explode('-', $monthYear)[0])) }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policyRates as $agentId => $agentData)
                                    @if (!empty($agentData['data']))
                                        <tr>
                                            <!-- Agent Name and Total -->

                                            <td>
                                                <span class="bs-tooltip" title="{{ $agentData['agent_name'] }}">
                                                    {{ \Illuminate\Support\Str::limit($agentData['agent_name'], 16) }}
                                                </span>
                                                <span class="badge badge-light-primary  me-2">
                                                    {{ array_sum($agentData['data']) }}
                                                </span>
                                                <span class="badge badge-light-danger  me-2">
                                                    {{ $agentData['days_since_last_policy'] }}
                                                </span>
                                            </td>

                                            <!-- Monthly Data for Each Agent -->
                                            @foreach ($agentData['data'] as $index => $policyCount)
                                                <td>{{ $policyCount ?? 0 }}</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
