@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Products</a></li>
<li class="breadcrumb-item active" aria-current="page">Add Insurance Product</li>
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
                <form action="{{ route('insurance-products.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>



                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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
