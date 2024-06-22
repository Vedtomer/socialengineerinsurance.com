@extends('admin.layouts.app')



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Claim</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manage Claim</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
            <div class="action-btn layout-top-spacing">
                <button id="add-list" class="btn btn-secondary">
                    <a href="{{ route('claims.create') }}">Add Claim</a>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <table id="html5-extension" class="table dt-table-hover">
                        <thead>
                            <tr>
                                <th>Claim Number</th>
                                <th>Customer Name</th>
                                <th>Policy Number</th>
                                <th>Agent Name</th>
                                <th>Claim Date</th>
                                <th>Incident Date</th>
                                <th>Amount Claimed</th>
                                <th>Amount Approved</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ $user->claim_number }}</td>
                                    <td>{{ $user->customer_name }}</td>
                                    <td>{{ $user->policy_number }}</td>
                                    <td>{{ $user->agent_name }}</td>
                                    <td>{{ $user->claim_date }}</td>
                                    <td>{{ $user->incident_date }}</td>
                                    <td>{{ $user->amount_claimed }}</td>
                                    <td>{{ $user->amount_approved }}</td>
                                    <td>{{ $user->status }}</td>
                                    <td class="text-center">
                                        <ul class="table-controls">
                                            <li>
                                                <a href="{{ route('claims.edit', $user->id) }}" class="bs-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-original-title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 p-1 br-8 mb-1">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
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
