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
<div class="row layout-top-spacing">

    {{-- <div class="seperator-header">
        <h4 class="">Policy Listing</h4>
    </div> --}}

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                                <th>Salary</th>
                                <th>Extn.</th>
                                <th>Avatar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                                <td>5421</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="usr-img-frame mr-2 rounded-circle">
                                            <img alt="avatar" class="img-fluid rounded-circle" src="../src/assets/img/boy.png">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-dark btn-sm">Open</button>
                                        <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Add other rows similarly -->
                        </tbody>
                    </table>
                </div>
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
