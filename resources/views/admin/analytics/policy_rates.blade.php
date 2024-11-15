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

// Mapping of month names to their numeric values
$monthMap = [
    'Jan' => 1,
    'Feb' => 2,
    'Mar' => 3,
    'Apr' => 4,
    'May' => 5,
    'Jun' => 6,
    'Jul' => 7,
    'Aug' => 8,
    'Sep' => 9,
    'Oct' => 10,
    'Nov' => 11,
    'Dec' => 12,
                                        ];
                                    @endphp
                                    @foreach ($monthLabels as $monthYear)
                                        @php
                                            // Extract month name and map it to its numeric value
                                            $monthNumeric = $monthMap[explode('-', $monthYear)[0]] ?? null;
                                        @endphp
                                        @if ($monthNumeric)
                                            <th>{{ date('M', mktime(0, 0, 0, $monthNumeric)) }}</th>
                                        @else
                                            <th>Invalid Month</th>
                                        @endif
                                    @endforeach

                                @endif
                                <th style="font-weight:bold;width:0px !important"> Day</th>

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
                                            <br>
                                            {{-- <span class="" style="color: #e0a82a; font-weight: 50;">
                                                Last policy:{{ $agentData['days_since_last_policy'] }} days ago
                                            </span> --}}

                                        </td>

                                        <!-- Monthly Data for Each Agent -->
                                        @foreach ($agentData['data'] as $index => $policyCount)
                                            <td>{{ $policyCount ?? 0 }}</td>
                                        @endforeach
                                     
                                        <td style="background-color: @php
                                        $days = $agentData['days_since_last_policy'];
                                        if ($days > 365) {
                                            echo '#8B0000';  // Dark Red
                                        } elseif ($days > 300) {
                                            echo '#B22222';  // Fire Brick Red
                                        } elseif ($days > 240) {
                                            echo '#CD0000';  // Deep Red
                                        } elseif ($days > 180) {
                                            echo '#DC143C';  // Crimson
                                        } elseif ($days > 120) {
                                            echo '#FF0000';  // Pure Red
                                        } elseif ($days > 90) {
                                            echo '#FF1493';  // Deep Pink
                                        } elseif ($days > 60) {
                                            echo '#FF4500';  // Orange Red
                                        } elseif ($days > 30) {
                                            echo '#FF6347';  // Tomato
                                        } elseif ($days > 15) {
                                            echo '#FF8C00';  // Dark Orange
                                        } elseif ($days > 7) {
                                            echo '#FFA500';  // Orange
                                        } elseif ($days > 5) {
                                            echo '#FFD700';  // Gold
                                        } elseif ($days > 3) {
                                            echo '#98FB98';  // Pale Green
                                        } else {
                                            echo '#228B22';  // Forest Green
                                        }
                                    @endphp; color: @php
                                        echo ($days > 7) ? 'white' : 'black';
                                    @endphp; font-weight: bold;">
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


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chartData = @json($chartData);
    </script>

<script>
    $(document).ready(function() {
        $('#html55-extension').DataTable({
            "responsive": true,
            "ordering": true,
            "order": [[5, "desc"]],
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    });
</script>
@endsection
