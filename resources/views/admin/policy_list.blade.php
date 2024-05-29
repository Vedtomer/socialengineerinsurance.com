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
<li class="breadcrumb-item active" aria-current="page">Policy List</li>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row justify-content-left mt-2">
                <div class="col-lg-4 mb-2">
                    <div class="datefil" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; margin-right: 50rem !important;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="left">
                        <select class="datefil form-select js-example-basic-single  select2" data-control="select2" data-placeholder="Select an option" onchange="filterAgent(this.value)">

                            <optgroup>
                                <option selected disabled>Select Agent</option>
                                @foreach ($agentData as $user)
                                <option value="{{ $user->id }}" @if(isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif> {{ $user->name }}</option>

                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>



            <div class="add" style="display: flex; align-items: center;">
                {{-- <h5 class="card-title">Royalsundaram</h5> --}}
                <div class="btns" style="margin-left: auto;">

                </div>
            </div>


            <div class="table-responsive">
                @if (isset($data) && count($data) > 0)
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th>S No</th>
                            {{-- <th>tgr</th> --}}
                            {{-- <th>policy_link</th> --}}
                            <th>Policy No.</th>
                            <th>Customer Name</th>
                            <th>Net Amount</th>
                            <th>GST</th>
                            <th>Premium</th>
                            <th>Commission</th>
                            <th>Upload Policy</th>
                            <th>Agent</th>
                            <th>Insurance Company</th>
                            <th>Payment By</th>

                            <th>Policy Start Date</th>
                            <th>Policy End Date</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                        <tr>

                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->policy_no }}</td>
                            <td>{{ $user->customername }}</td>

                            <td>{{ $user->net_amount }}</td>
                            <td>{{ $user->gst }}</td>
                            {{-- <td>{{ $user->password }}</td> --}}
                            <td>{{ $user->premium }}</td>
                            <td>{{ $user->agent_commission }}</td>

                            {{-- <td>
                    @if (empty($user->policy_link)) --}}
                            {{-- <button>Add Button</button> --}}
                            {{-- <input type="file">
                    @else
                        {{ $user->policy_link }}
                            @endif
                            </td> --}}

                            <td>
                                @if (empty($user->policy_link))
                                <form action="{{ route('updateagentid', ['royalsundaram_id' => $user->id]) }}" method="post" enctype="multipart/form-data" onchange="submitForm(this)">
                                    @csrf
                                    <input type="file" name="policy_file">
                                </form>
                                @else
                                <a href="{{ $user->policy_link }}" download="{{ $user->policy_link }}"><i class="fa fa-download"> Download</i></a>
                                @endif
                            </td>



                            {{-- <td>{{ $user->agent_id }}</td> --}}
                            {{-- <td>{{ optional($user->agent)->name }}</td> --}}
                            <td>
                                @if (optional($user->agent)->name)
                                {{ $user->agent->name }}
                                @else
                                <select class="form-select js-example-basic-single select2" data-control="select2" data-placeholder="Select an option" onchange="confirmAgentChange(this); location = this.value;">
                                    <option value="" selected disabled>Select Agent</option>
                                    @foreach ($agentData as $record)
                                    {{-- @if ($agent && is_object($agent) && $agent->status == 1) --}}
                                    <option value="{{ route('updateagentid', ['agent_id' => $record->id ,'royalsundaram_id' => $user->id ]) }}">
                                        {{ $record->name }}
                                    </option>
                                    {{-- @endif --}}
                                    @endforeach
                                </select>

                                @endif
                            </td>
                            <td>{{ $user->insurance_company }}</td>
                            <td>{{ $user->payment_by }}</td>




                            <td>
                                {{ date('M d, Y', strtotime($user->policy_start_date)) }}
                            </td>
                            <td>
                                {{ date('M d, Y', strtotime($user->policy_end_date)) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>No Policy Found.</p>
                @endif
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