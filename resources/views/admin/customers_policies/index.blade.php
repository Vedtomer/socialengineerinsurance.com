@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Policies</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manage Customer Policies</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary">
                <a href="{{ route('customer-policies.create') }}">Add Customers Policy</a>
            </button>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <table id="html5-extension" class="table dt-table-hover">
                        <thead>
                            <tr>
                                <th>Policy No.</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Net Amount</th>
                                <th>GST</th>
                                <th>Premium</th>
                                <th>Insurance Company</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerPolicies as $key => $policy)
                                <tr>
                                    <td>
                                        {{ $policy->policy_no }}
                                        @if (!empty($policy->policy_link))
                                            <a href="{{ $policy->policy_link }}" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-download ml-2">
                                                    <path
                                                        d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3">
                                                    </line>
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $policy->policy_start_date }}</td>
                                    <td>{{ $policy->policy_end_date }}</td>
                                    <td>{{ $policy->user_name }}</td>
                                    <td>{{ $policy->status }}</td>
                                    <td>{{ $policy->net_amount }}</td>
                                    <td>{{ $policy->gst }}</td>
                                    <td>{{ $policy->premium }}</td>
                                    <td>{{ $policy->insurance_company }}</td>
                                    <td class="text-center">
                                        <ul class="table-controls">
                                            <li>
                                                <a href="{{ route('customer-policies.edit', $policy->id) }}"
                                                    class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="feather feather-edit-2 text-warning">
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
