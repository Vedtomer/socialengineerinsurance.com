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
                    <div class="datefil" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; margin-right: 18rem !important;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <select class="datefil form-select js-example-basic-single select2" data-control="select2" data-placeholder="Select an option" onchange="filterAgent(this.value)">
                        <option disabled>Select Agent</option>
                        @foreach ($agent as $user)
                        <option value="{{ $user->id }}" @if(isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif>{{ $user->name }}</option>

                        @endforeach
                    </select>

                </div>

                <div class="left ml-3 mb-2 mr-5">

                    <select class="form-select js-example-basic-single  select2" data-control="select2" data-placeholder="Select an option" onchange="filterPayment(this.value)">

                        <optgroup>
                            <option selected disabled>Select Payment Mode</option>
                            <option value="cash" @if(isset($_GET['payment_mode']) && $_GET['payment_mode']==="cash" ) selected @endif>Cash</option>

                            <option value="online" @if(isset($_GET['payment_mode']) && $_GET['payment_mode']==="online" ) selected @endif>Online</option>

                            {{-- <option value="{{ $data->id }}" > {{ $data->payment_mode }}</option> --}}


                        </optgroup>
                    </select>
                </div>


                <div class="add ml-3" style="display: flex; align-items: center;">

                    <div class="btns" style="margin-left: auto;">
                        <a id="openModalBtn" href="{{ route('add.transaction') }}" class="btn btn-secondary mb-2">Add Transaction</a>
                        {{-- <a  href="{{ route('admin.user') }}" class="btn btn-secondattry ml-2">Back</a> --}}
                    </div>
                </div>

            </div>
        </div>
        {{-- <h5 class="card-title">TRANSACTION</h5> --}}


        <div class="table-responsive">
            <table class="mb-0 table">
                <thead>
                    <tr>
                        <th>S No</th>
                        <th>Agent Id</th>
                        <th>payment_mode</th>
                        <th>transaction_id</th>
                        <th>amount</th>
                        <th>payment_date </th>

                        {{-- <th>Updated Date</th>
    <th>Updated Time</th> --}}

                        {{-- <th></th> --}}

                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $user)
                    <tr>

                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $user->agent->name }}</td>
                        <td>{{ $user->payment_mode }}</td>
                        <td>{{ $user->transaction_id }}</td>
                        <td>{{ $user->amount }}</td>

                        <td>{{ $user->payment_date }}</td>



                        {{-- <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $user->updated_at->toDateString() }}</td> --}}
                        {{-- <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y h' ) }}</td> --}}
                        {{-- <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('H:i:s' ) }}</td> --}}



                    </tr>
                    @endforeach
                </tbody>
            </table>
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