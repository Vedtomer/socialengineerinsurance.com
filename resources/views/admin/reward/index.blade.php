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
<li class="breadcrumb-item"><a href="#">Reward</a></li>
<li class="breadcrumb-item active" aria-current="page">Policy Listing</li>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="top" style="display: flex;">
                <div class="col-3 mb-4 mr-5" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; margin-right: 50rem !important;">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
                <div class="left ml-5">
                    <select class="form-select js-example-basic-single  select2" data-control="select2" data-placeholder="Select an option" onchange="filterAgent(this.value)">
                        <option selected disabled>Select Agent</option>
                        @foreach ($agents as $user)
                        <option value="{{ $user->id }}" @if (isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif>
                            {{ $user->name }}
                        </option>
                        @endforeach

                    </select>
                </div>
            </div>



            <div class="add" style="display: flex; align-items: center;">
                {{-- <h5 class="card-title">Royalsundaram</h5> --}}
                <div class="btns" style="margin-left: auto;">

                </div>
            </div>


            <div class="table-responsive">
                @if (isset($points) && count($points) > 0)
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Agent</th>
                            <th>Point</th>
                            <th>TDS</th>
                            <th>Amount Paid</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($points as $point)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $point->agent->name }}</td>
                            <td>{{ $point->points }}</td>
                            <td>{{ $point->tds }}</td>
                            <td>{{ $point->amount_to_be_paid }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($point->created_at)->isoFormat('MMM DD, YYYY h:mm A') }}
                            </td>

                            <td><span class="badge badge-{{ $point->status == 'completed' ? 'success' : ($point->status == 'rejected' ? 'danger' : 'secondary') }} title-case">{{ $point->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>No Record Found.</p>
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