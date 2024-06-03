@extends('admin.layouts.app')



@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Reward</a></li>
<li class="breadcrumb-item active" aria-current="page">Policy Request</li>
@endsection

@section('content')




<div class="row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                       
                            <tr>
                                <th>S No</th>
                                <th>Agent</th>
                                <th>Point</th>
                                <th>TDS(5%)</th>
                                <th>Amount Pay</th>
                                <th>Policy Month</th>
                                <th>Requested Date</th>
                               
                                <th>Action</th>
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
                                @if(!empty($point->policy_period_month_year))
                                    {{ \Carbon\Carbon::parse($point->policy_period_month_year)->format('F Y') }}
                                @else
                                    <!-- Optionally, you can display a default value or leave it empty -->
                                    N/A
                                @endif
                            </td>

                            <td>{{ date('M d, Y', strtotime($point->created_at)) }}</td>

                            <td>
                                <button class="btn btn-warning"
                                    onclick="redeemSuccess({{ $point->id }}, '{{ $point->points }}', '{{ $point->tds }}', '{{ $point->amount_to_be_paid }}','{{ $point->agent->name }}')">Approve
                                    Redeem</button>
                                <button class="btn btn-danger"
                                    onclick="cancelRedeem({{ $point->id }})">Cancel Redeem</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>



 <!-- Include SweetAlert from CDN -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
     function redeemSuccess(pointId, points, tds, amountToBePaid, agent) {
         Swal.fire({
             title: "Redeem Process Success",
             html: "Agent: <b>" + agent + "</b><br>" +
                 "Points: " + points + "<br>" +
                 "TDS: " + tds + "<br>" +
                 "Amount To Be Paid: " + amountToBePaid + "<br><br>" +
                 "Do you want to proceed?",
             icon: "warning",
             showCancelButton: true,
             confirmButtonText: "Yes, Proceed",
             cancelButtonText: "Cancel",

         }).then((result) => {
             if (result.isConfirmed) {
                 var token = '{{ csrf_token() }}';
                 $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': token
                     }
                 });

                 $.post('/admin/redeem/success/' + pointId)
                     .done(function(response) {
                         location.reload();
                     })
                     .fail(function(error) {
                         console.error(error);
                         Swal.fire({
                             title: "Error",
                             text: "An error occurred while processing your request.",
                             icon: "error",
                             showConfirmButton: false,
                             timer: 2000
                         });
                     });
             }
         });
     }

     function cancelRedeem(pointId) {
         Swal.fire({
             title: "Cancel Redeem",
             text: "Are you sure you want to cancel this redemption request?",
             icon: "warning",
             showCancelButton: true,
             confirmButtonText: "Yes, Cancel",
             cancelButtonText: "No, Keep it",
             // customClass: {
             //     title: 'text-right'
             // }
         }).then((result) => {
             if (result.isConfirmed) {
                 var token = '{{ csrf_token() }}';
                 $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': token
                     }
                 });

                 $.post('/admin/redeem/cancel/' + pointId)
                     .done(function(response) {
                         location.reload();
                     })
                     .fail(function(error) {
                         console.error(error);
                         Swal.fire({
                             title: "Error",
                             text: "An error occurred while canceling the request.",
                             icon: "error",
                             showConfirmButton: false,
                             timer: 2000
                         });
                     });
             }
         });
     }
 </script>

@endsection

