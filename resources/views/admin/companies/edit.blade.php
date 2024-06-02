@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Company</a></li>
<li class="breadcrumb-item active" aria-current="page">Add Insurance Company</li>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-6 mx-auto mt-4">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="errors">
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                    @endforeach
                    @endif
                </div>
                <form action="{{ isset($company) ? route('companies.update', $company->id) : route('companies.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @if (isset($company))
                    @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="name">Company Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', isset($company) ? $company->name : '') }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="image">Update Company Logo</label>
                        <input type="file" class="form-control" name="file" {{ isset($company) ? '' : 'required' }}>
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="active" value="1" {{ old('status', isset($company) && $company->status == 1 ? 'checked' : '') }}>
                                <label class="form-check-label" for="active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inactive" value="0" {{ old('status', isset($company) && $company->status == 0 ? 'checked' : '') }}>
                                <label class="form-check-label" for="inactive">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ isset($company) ? 'Update' : 'Submit' }}</button>
                        <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
