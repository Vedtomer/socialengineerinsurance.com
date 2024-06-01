@extends('admin.layouts.app')



@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Transaction</a></li>
<li class="breadcrumb-item active" aria-current="page">Add Transaction</li>
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







<script>
    function toggleTransactionIDInput() {
        var paymentMode = document.getElementById("payment_mode").value;
        var transactionIDField = document.getElementById("transaction_id_field");
        if (paymentMode === "cash") {
            transactionIDField.style.display = "none";
        } else {
            transactionIDField.style.display = "block";
        }
    }

    function getDate() {
        var today = new Date();
        document.getElementById("date").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    }
</script>
<script>
    $(document).ready(function() {
        $('.dropdown-submenu a.test').on("click", function(e) {
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    });
</script>
@endsection

