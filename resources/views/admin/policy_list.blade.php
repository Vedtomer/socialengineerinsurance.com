@extends('admin.layouts.app')



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Agent</a></li>
    <li class="breadcrumb-item active" aria-current="page">Policy List</li>
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
                            <th>Policy No.</th>
                            <th>Policy Type</th>
                            <th>Customer Name</th>
                            <th>Policy Date</th>
                            <th>Net Amount</th>
                            <th>GST</th>
                            <th>Premium</th>
                            <th>Commission</th>
                            <th>Upload Policy</th>
                            <th>Agent</th>
                            <th>Insurance Company</th>
                            <th>Payment By</th>
                            <th>Discount</th>
                            <th>Payout</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $user)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->policy_no }}</td>
                            <td>
                                @if($user->policy_type == NULL)
                                <span>E-Rickshaw</span>
                                @else
                                <span>{{ $user->policy_type }}</span>
                                @endif
                            </td>
                            <td>{{ $user->customername }}</td>
                            <td> {{ date('M d, Y', strtotime($user->policy_start_date)) }} </td>
                            <td>{{ $user->net_amount }}</td>
                            <td>{{ $user->gst }}</td>
                            <td>{{ $user->premium }}</td>
                            <td>{{ $user->agent_commission }}</td>
                            <td>
                                @if (empty($user->policy_link))
                                    <form action="{{ route('updateagentid', ['royalsundaram_id' => $user->id]) }}"
                                        method="post" enctype="multipart/form-data" onchange="submitForm(this)">
                                        @csrf
                                        <input type="file" name="policy_file">
                                    </form>
                                @else
                                    <a href="{{ $user->policy_link }}" download="{{ $user->policy_link }}"><i
                                            class="fa fa-download"> Download</i></a>
                                @endif
                            </td>
                            <td>
                                @if (optional($user->agent)->name)
                                    {{ $user->agent->name }}
                                @else
                                    <select class="form-select js-example-basic-single select2"
                                        data-control="select2" data-placeholder="Select an option"
                                        onchange="confirmAgentChange(this); location = this.value;">
                                        <option value="" selected disabled>Select Agent</option>
                                        @foreach ($agentData as $record)
                                            <option
                                                value="{{ route('updateagentid', ['agent_id' => $record->id, 'royalsundaram_id' => $user->id]) }}">
                                                {{ $record->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                            <td>{{ $user->Company->name }}</td>
                            <td>{{ $user->payment_by }}</td>

                            <td>
                                {{ $user->discount }}
                            </td>
                            <td>
                                {{ $user->payout }}
                            </td>
                            <td>
                                <button class="btn btn-danger" onclick="policyDelete('{{$user->id}}')" >Delete</button>
                                {{-- <button class="btn btn-danger">Delete</button> --}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
     function policyDelete(id) {
         Swal.fire({
             title: "Please confirm to Delete",
             text: "Do you want to proceed?",
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

                 $.post('/admin/policy-list/delete/' + id)
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
                             timer: 4000
                         });
                     });
             }
         });
     }

 </script>
