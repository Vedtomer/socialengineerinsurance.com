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
<div class="col-lg-6">
    <div class="main-card mb-3 card">
        <div class="card-body">                    
            <form method="post" action="{{route('add.transaction')}}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Agent</label>
                    <select class="form-select form-control js-example-basic-single select2" data-control="select2" data-placeholder="Select an option" name="agent_id">
                        <option selected disabled>Select Agent</option>
                        @foreach ($data as $user)
                            <option value="{{ $user->id }}" @if(isset($_GET['agent_id']) && $user->id == $_GET['agent_id']) selected @endif> {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="payment_mode">Payment Mode</label>
                    <select class="form-control" id="payment_mode" name="payment_mode" required onchange="toggleTransactionIDInput()">
                        <option selected disabled>Select Payment Mode</option>
                        <option value="cash">Cash</option>
                        <optgroup label="Online">
                            <option value="google_pe">Google Pay</option>
                            <option value="phone_pe">PhonePe</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="netbanking">Netbanking</option>
                            <option value="paytm">Paytm</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="mb-3" id="transaction_id_field" style="display: none;">
                    <label>Transaction ID</label>
                    <input type="text" class="form-control" name="transaction_id">
                </div>
                
                <div class="mb-3">
                    <label>Amount</label>
                    <input type="text" class="form-control" name="amount" required>
                </div>
                
                <div class="mb-3">
                    <label>Payment Date</label>
                    <input type="date" class="form-control" name="payment_date" id="date" required>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('admin.transaction') }}" class="btn btn-secondary">Back</a>
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