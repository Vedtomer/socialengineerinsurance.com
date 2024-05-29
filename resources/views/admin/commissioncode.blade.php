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
<li class="breadcrumb-item active" aria-current="page">Commission code</li>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="main-card mb-3 mt-3 card">
        <div class="card-body">

            <div class="row justify-content-left mt-2">
                <div class="col-lg-4 mb-2">
            <div class="datefil" id="reportrange"
            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
                </div>
                <div class="col-lg-4 mb-2">
        <div class="right " >
            <select class="datefil form-select js-example-basic-single  select2" data-control="select2" data-placeholder="Select an option" onchange="filterAgent(this.value)">
    
                <optgroup>
                    <option selected disabled>Select Agent</option>
                    @foreach ($agent as $user)
                    <option value="{{ $user->id }}" @if(isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif> {{ $user->name }}</option>
                    
                    @endforeach
                </optgroup>
            </select>
        </div>
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
                            <th style="width: 5%" scope="col">Commission Code</th>
                            <th style="width: 20%" scope="col">Name</th>
                            
                           
                            <th style="width: 20%" scope="col">Email</th>
                            
                            <th style="width: 20%" scope="col">Mobile</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                            <tr @if ($user->commissions->isEmpty() || $user->commissions->sum('commission') == 0) style="background-color: #374181 ;" @endif >
                                <td>{{ $key + 1 }}</td>
                                <td style=" ">
                                    @foreach ($user->commissions as $commission)
                                        <div style="display: inline-block; margin-right: 10px; white-space: nowrap;">
                                            <button class="btn btn-secondary waves-effect waves-light"
                                                onclick="copyCommissionCode('{{ $commission['commission_code'] }}')">
                                                {{ $commission['commission_code'] }}
                                            </button>
                                            <span class="badge badge-warning">{{ $commission['commission'] }}
                                                {!! $commission->commission_type == 'percentage' ? '%' : '&#x20B9;' !!}</span>
                                        </div>
                                        <hr>
                                    @endforeach
                                </td>
                                <td>{{ $user->name }}</td>
                                
                                
                                <td>{{ $user->email }}</td>
                               
                                <td>{{ $user->mobile_number }}</td>
                               
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