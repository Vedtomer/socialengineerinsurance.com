@extends('admin.layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Customer </a></li>
    <li class="breadcrumb-item active" aria-current="page">Manage Customer</li>
@endsection

@section('content')
    <div class="row">
        <!-- Existing Add Customers button -->
        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
            <div class="action-btn layout-top-spacing">
                <button id="add-list" class="btn btn-secondary">
                    <a href="{{ route('customers.create') }}">Add Customers</a>
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
                                <th>Sr.No.</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Aadhar No.</th>
                                <th>PAN No.</th>
                                {{-- <th>State</th>
                                <th>City</th> --}}
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->mobile_number }}</td>
                                    <td>
                                        {{ $user->aadhar_number }}
                                        @if ($user->aadhar_document)
                                            <a href="{{ asset('storage/aadhar/' . $user->aadhar_document) }}"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-download ml-2">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3">
                                                    </line>
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->pan_number }}
                                        @if ($user->pan_document)
                                            <a href="{{ asset('storage/pancard/' . $user->pan_document) }}" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-download ml-2">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3">
                                                    </line>
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                    {{-- <td>{{ $user->state }}</td>
                                    <td>{{ $user->city }}</td> --}}
                                    <td>


                                        <button type="button" class="badge badge-light-info mb-2 me-4"
                                            data-bs-toggle="modal" data-bs-target="#addressModal{{ $user->id }}">
                                            view
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <ul class="table-controls">
                                            <li><a href="{{ route('customers.edit', $user->id) }}" class="bs-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-original-title="Edit" aria-label="Edit"
                                                    data-bs-original-title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-2 p-1 br-8 mb-1">
                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('customers.changePassword', $user->id) }}"
                                                    class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-original-title="Change Password" aria-label="Change Password">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-lock p-1 br-8 mb-1">
                                                        <rect x="3" y="11" width="18" height="11" rx="2"
                                                            ry="2"></rect>
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                                <!-- Address Modal -->
                                <div class="modal fade" id="addressModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="addressModalCenterTitle" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addressModalCenterTitle">Full Address</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-x">
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18"></line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h4 class="modal-heading mb-4 mt-2">Address Details</h4>
                                                <p class="modal-text">
                                                    <strong>Full Address:</strong> {{ $user->address }}<br>
                                                    <strong>City:</strong> {{ $user->city }}<br>
                                                    <strong>State:</strong> {{ $user->state }}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-light-dark _effect--ripple waves-effect waves-light"
                                                    data-bs-dismiss="modal">Discard</button>
                                                <button type="button"
                                                    class="btn btn-primary _effect--ripple waves-effect waves-light">Copy
                                                    Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
