@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
@endsection

@section('content')

    <div class="row layout-top-spacing">
        <div class="col-lg-8 mx-auto mt-4">
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
                    <form class="row g-3" action="{{ route('customers.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="mobile_number" class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="aadhar_number" class="form-label">Aadhar Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pan_number" class="form-label">PAN Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                        </div>
                        <!--  -->

                          <!--  -->
                          <div class="col-md-12">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}">
                        </div>
                        <!--  -->

                        <div class="col-md-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                        </div>
                        <!--  -->
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="aadhar_document" class="form-label">Upload Aadhar Card</label>
                            <input type="file" class="form-control" id="aadhar_document" name="aadhar_document">
                        </div>

                        <div class="col-md-6">
                            <label for="pan_document" class="form-label">Upload PAN Card</label>
                            <input type="file" class="form-control" id="pan_document" name="pan_document">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
