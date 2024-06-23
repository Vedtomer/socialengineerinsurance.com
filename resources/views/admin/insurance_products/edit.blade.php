@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Products</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit Insurance Product</li>
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
                <form action="{{ route('insurance-products.update', $insuranceProduct->id) }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $insuranceProduct->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="1" {{ $insuranceProduct->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$insuranceProduct->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('insurance-products.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
