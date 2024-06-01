@extends('admin.layouts.app')

@push('styles')



@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Transaction</a></li>
<li class="breadcrumb-item active" aria-current="page">Transaction Listing</li>
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
      



<div class="row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Agent </th>
                            <th>Payment Mode</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Payment Date </th>
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

            </tr>
            @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection
