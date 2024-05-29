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
        <div class="main-card mb-3 mt-3 card">
            <div class="card-body">
                <div class="errors">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif
                </div>
                <form action="{{ route('agent') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                            placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Enter email">
                    </div>

                    <div class="mb-3">
                        <label for="state">State</label>
                        <input type="text" class="form-control" name="state" value="{{ old('state') }}"
                            placeholder="Enter state">
                    </div>
                    <div class="mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city') }}"
                            placeholder="Enter city">
                    </div>
                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                            placeholder="Enter address">
                    </div>
                    <div class="mb-3">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="text" class="form-control" name="mobile_number" value="{{ old('mobile_number') }}"
                            onkeypress="allowOnlyNumbers(event)" placeholder="Enter mobile number" required>
                    </div>

                    <div class="mb-3">
                        <label class="d-block"> Cut and Pay</label>
                        <div class="form-check-inline">
                            <label class="form-check-label mr-3">
                                <input type="radio" class="form-check-input" name="cut_and_pay" value="1"
                                    > Yes
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="cut_and_pay" value="0"
                                  checked > No
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
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