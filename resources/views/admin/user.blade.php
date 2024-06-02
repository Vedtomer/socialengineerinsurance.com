@extends('admin.layouts.app')

@push('styles')



@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Agent</a></li>
<li class="breadcrumb-item active" aria-current="page">Agent Listing</li>
@endsection

@section('content')

<div class="row">
    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary"><a id="openModalBtn" href="{{ route('agent') }}">Add Agent</a></button>
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
                            <th>Sr.No.</th>
                            <th>Name</th>
                            <th>Policy</th>
                            <th>Premium</th>
                            <th>Earn Points</th>
                            <th>City</th>
                            <th>Mobile</th>
                            <th>Cut And Pay</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                @php
                                $policyCount = count($user->Policy);
                                @endphp
                                <span class="mb-2 mr-2">{{ $policyCount }}</span>
                            </td>
                            <td>
                                @php
                                $totalagentpremium = 0;
                                @endphp
                                @foreach ($user->Policy as $record)
                                @php
                                $totalagentpremium += $record->premium;
                                @endphp
                                @endforeach
                                <span class="mb-2 mr-2">{{ $totalagentpremium }}</span>
                            </td>
                            <td>
                                @php
                                $totalagentcommission = 0;
                                @endphp
                                @foreach ($user->Policy as $record)
                                @php
                                $totalagentcommission += $record->agent_commission;
                                @endphp
                                @endforeach
                                <span class="mb-2 ml-2 mr-2 d-flex "><i class="fa fa-rupee pr-2" style="font-size:20px"></i> {{ $totalagentcommission }}</span>
                            </td>
                            {{-- <td>{{ $user->email }}</td> --}}
                            <td>{{ $user->city }}</td>
                            <td>{{ $user->mobile_number }}</td>
                            <td>
                                @if ($user->cut_and_pay == 1)
                                <span class="text-success">Yes</span>
                                @else
                                <span class="text-danger">No</span>
                                @endif
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

                                        <a class="dropdown-item " href="{{ route('agent.edit', $user->id) }}">Edit</a>
                                        <a class="dropdown-item " href="{{ route('agent.change.password', $user->id) }}">Change Paasword</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('agent.commission', $user->id) }}">Manage Commission</a>
                                    </div>
                                </div>
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

