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
<div class="col-lg-6">
    <div class="main-card mb-3 card">
        <div class="card-body">

            @If(!empty($data))

            <form method="post" action="{{route('agent.edit' , $data->id)}}" enctype="multipart/form-data">
                @csrf


                <div class="mb-3">
                    <label>name</label>
                    <input type="text" class="form-control" name="name" value="{{$data->name}}">
                </div>


                <div class="mb-3">
                    <label>Email address</label>
                    <input type="email" class="form-control" name="email" value="{{$data->email}}">
                </div>

                <div class="mb-3">
                    <label>State</label>
                    <input type="text" class="form-control" name="state" value="{{$data->state}}">
                </div>

                <div class="mb-3">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" value="{{$data->city}}">
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="{{$data->address}}">
                </div>

                <div class="mb-3">
                    <label>Mobile - Number</label>
                    <input type="text" class="form-control" name="mobile_number" value="{{$data->mobile_number}}" onkeypress="allowOnlyNumbers(event)">
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label class="d-block">Cut and Pay</label>
                        <div class="form-check-inline">
                            <label class="form-check-label mr-3">
                                <input type="radio" class="form-check-input" name="cut_and_pay" value="1" {{$data->cut_and_pay ? 'checked' : ''}}> Yes
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="cut_and_pay" value="0" {{$data->cut_and_pay ? '' : 'checked'}}> No
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="d-block">Status</label>
                        <div class="form-check-inline">
                            <label class="form-check-label mr-3">
                                <input type="radio" class="form-check-input" name="active" value="1" {{$data->status ? 'checked' : ''}}> Active
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="active" value="0" {{$data->status ? '' : 'checked'}}> Inactive
                            </label>
                        </div>
                    </div>
                </div>






                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>


            @else

            <form method="post" action="{{ route('agent.change.password', $id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>


                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                </div>

            </form>

            @endif
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
