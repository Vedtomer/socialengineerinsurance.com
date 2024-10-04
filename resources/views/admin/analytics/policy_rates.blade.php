@extends('admin.layouts.app')

@section('title', 'Agent Policy Rates')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Policy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Agent Policy </li>
@endsection

@section('content')
    <div class="row layout-top-spacing">


        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-three">
                {{-- <div class="widget-heading">
                    <div class="">
                        <h5 class="">Unique Visitors</h5>
                    </div>

                    <div class="task-action">
                        <div class="dropdown ">
                            <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                            </a>

                            <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                                <a class="dropdown-item" href="javascript:void(0);">View</a>
                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="widget-content">
                    <div id="uniqueVisits"></div>
                </div>
            </div>
        </div>


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <table id="html55-extension" class="table dt-table-hover" style="width:100%">
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
                                            <span class="bs-tooltip agent-name"
                                                data-full-name="{{ $agentData['agent_name'] }}">
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

    <script></script>
@endsection
