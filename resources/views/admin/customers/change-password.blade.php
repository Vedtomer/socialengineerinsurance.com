@extends('admin.layouts.customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
                    <form class="row g-3" action="{{ route('customers.changePassword', $customer->id) }}" method="post" autocomplete="off">
                        @csrf
                        <div class="col-md-12">
                            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="col-md-12">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
