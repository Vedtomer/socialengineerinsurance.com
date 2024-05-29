@extends('admin.layouts.app')

@push('styles')


<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/plugins/src/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/plugins/css/dark/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">

<!-- END PAGE LEVEL STYLES -->
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Agent</a></li>
<li class="breadcrumb-item active" aria-current="page">Agent Listing</li>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">

            <div class="row justify-content-left mt-2">
                <div class="col-lg-4 mb-2">
                    <div class="datefil " id="reportrange" style="background: #fff; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="">
                        <select class=" datefil form-select js-example-basic-single  select2" data-control="select2" data-placeholder="Select an option" onchange="filterAgent(this.value)">

                            <optgroup>
                                <option selected disabled>Select Agent</option>
                                @foreach ($agent as $user)
                                <option value="{{ $user->id }}" @if(isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif> {{ $user->name }}</option>

                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="right ml-3">
                    <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <a href="{{ route('download.excel') }}" class="btn btn-primary">Download Excel</a>
                    </form>

                </div>
            </div>

            <div class="add" style="display: flex; align-items: center;">

                <div class="btns" style="margin-left: auto;">
                    <a id="openModalBtn" href="{{ route('agent') }}" class="btn btn-secondary mb-2">Add Agent</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="mb-0 table table-striped table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th style="width: 5%" scope="col">Sr. No.</th>
                            <th style="width: 20%" scope="col">Name</th>
                            <th style="width: 20%" scope="col">Policy</th>
                            <th style="width: 20%" scope="col">Premium</th>
                            <th style="width: 20%" scope="col">Earn Points</th>
                            <th style="width: 20%" scope="col">Email</th>
                            <th style="width: 20%" scope="col">City</th>
                            <th style="width: 20%" scope="col">Mobile</th>
                            <th style="width: 20%" scope="col">Cut And Pay</th>
                            <th style="width: 20%" scope="col">Commission</th>
                            <th style="width: 20%" scope="col">Transaction</th>
                            <th style="width: 15%" scope="col">Update</th>
                            <th style="width: 15%" scope="col">Change Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                        <tr @if (count($user->Policy) == 0) style="background-color: #374181 ;" @endif>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                @php
                                $policyCount = count($user->Policy);
                                @endphp
                                <span class="mb-2 mr-2">{{ $policyCount }}</span>
                            </td>
                            <td>
                                @php
                                $totalagentpremium = 0;
                                @endphp
                                @foreach ($user->Policy as $record)
                                @php
                                $totalagentpremium += $record->premium;
                                @endphp
                                @endforeach
                                <span class="mb-2 mr-2">{{ $totalagentpremium }}</span>
                            </td>
                            <td>
                                @php
                                $totalagentcommission = 0;
                                @endphp
                                @foreach ($user->Policy as $record)
                                @php
                                $totalagentcommission += $record->agent_commission;
                                @endphp
                                @endforeach
                                <span class="mb-2 ml-2 mr-2 d-flex "><i class="fa fa-rupee pr-2" style="font-size:20px"></i> {{ $totalagentcommission }}</span>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ $user->mobile_number }}</td>
                            <td>
                                @if ($user->cut_and_pay == 1)
                                <span class="text-success">Yes</span>
                                @else
                                <span class="text-danger">No</span>
                                @endif
                            </td>

                            <td>
                                <a class="btn  mr-2" href="{{ route('agent.commission', $user->id) }}"><i class="fa fa-edit" style="font-size:24px"></i></a>
                            </td>
                            <td>
                                <a class="btn  mr-2" href="{{ route('admin.transaction', $user->id) }}"><i class="metismenu-icon pe-7s-look" title="Download" style="font-size:30px;text-align: center;"></i></a>
                            </td>
                            <td>
                                <a class="btn " href="{{ route('agent.edit', $user->id) }}"><i class="fa fa-edit" style="font-size:24px"></i></a>
                            </td>

                            <td>
                                <a class="btn " href="{{ route('agent.change.password', $user->id) }}"><i class="fa fa-lock" style="font-size:24px"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('asset/admin/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/buttons.print.min.js') }}"></script>
<script src="{{ asset('asset/admin/plugins/src/table/datatable/custom_miscellaneous.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endpush