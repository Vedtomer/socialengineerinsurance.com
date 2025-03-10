@extends('admin.layouts.customer')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Insurance</a></li>
<li class="breadcrumb-item active" aria-current="page">Insurance Product</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary"><a id="openModalBtn" href="{{ route('insurance-products.create') }}">Add Insurance Product</a></button>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">APP ICON</th>
                    <th scope="col">Name</th>

                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($insuranceProducts as $company)
                <tr>
                    <td>
                        <div class="media">
                            <div class="avatar avatar-xl">
                                <img alt="avatar" src="{{$company->icon}}" class="rounded-circle" />
                            </div>
                        </div>
                    </td>
                    <td>{{$company->name}}</td>


                    <td class="text-center">
                        @if($company->status === 1)
                        <span class="badge badge-light-success">Active</span>
                        @else
                        <span class="badge badge-light-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('insurance-products.edit', $company->id) }}" class="action-btn btn-edit bs-tooltip me-2" data-toggle="tooltip" data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <p>No Insurance Product.</p>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
