@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Company</a></li>
<li class="breadcrumb-item active" aria-current="page">Insurance Company</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary"><a id="openModalBtn" href="{{ route('companies.create') }}">Add Company</a></button>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Logo</th>
                    <th scope="col">Name</th>
                    <th class="text-center" scope="col">Company ID<small>(For excel upload)<small> </th>
                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                <tr>
                    <td>
                        <div class="media">
                            <div class="avatar me-2">
                                <img alt="avatar" src="{{$company->image}}" class="rounded-circle" />
                            </div>
                        </div>
                    </td>
                    <td>{{$company->name}}</td>
                    <td>{{ strtoupper($company->slug) }}</td>

                    <td class="text-center">
                        @if($company->status === 1)
                        <span class="badge badge-light-success">Active</span>
                        @else
                        <span class="badge badge-light-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('companies.edit', $company->id) }}" class="action-btn btn-edit bs-tooltip me-2" data-toggle="tooltip" data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <p>No Company added.</p>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
