@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Profile</a></li>
<li class="breadcrumb-item active" aria-current="page">Update</li>
@endsection

@section('content')


<div class="row">
    <div class="col-md-12 layout-top-spacing mb-2">
    </div>
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget">
            <div class="widget-content widget-content-area br-8">
                <div class="row">
                    <div class="col-lg-11 mx-auto mt-5">
                        <ul class="nav nav-pills nav-pills-inline justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" href="#profile-details" aria-selected="true" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg> Profile Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" href="#change-password" aria-selected="false" role="tab" tabindex="-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key">
                                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                        </path>
                                    </svg> Change Password
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="profile-details" class="tab-pane fade active show" role="tabpanel">
                                <form class="needs-validation mt-4" method="post" action="{{route("admin.update")}}" autocomplete="off" novalidate="novalidate" block-unload="true" enctype="multipart/form-data">
                                    <div class="row">
                                        @csrf
                                        {{-- <input type="hidden" name="_method" value="put"> --}}
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="first-name-input"> Name</label>
                                                <input id="first-name-input" type="text" name="name" class="form-control" placeholder="First Name" autocomplete="off" required="required" maxlength="255" spellcheck="false" autofocus="true" value="{{$user->name}}">
                                                <div class="invalid-feedback">Please enter name</div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="mobile-input">Mobile Number</label>
                                                <input id="mobile-input" type="text" name="mobile_number" class="form-control" placeholder="Mobile Number" autocomplete="off" required="required" maxlength="15" spellcheck="false" value="{{$user->mobile_number}}">
                                                <div class="invalid-feedback">Please enter mobile number</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="email-input">Email Address</label>
                                                <input id="email-input" type="email" name="email" class="form-control" placeholder="Email Address" autocomplete="off" required="required" maxlength="255" spellcheck="false" value="{{$user->email}}">
                                                <div class="invalid-feedback">Please enter email address</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="email-input">Update Image</label>
                                                <input id="email-input" type="file" name="profile_image" class="form-control" placeholder="Email Address" autocomplete="off" required="required" maxlength="255" spellcheck="false" value="{{$user->email}}">
                                                <div class="invalid-feedback">Please enter email address</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mt-3 mb-5 text-start">
                                                <button type="submit" class="btn btn-outline-info mb-2 me-4 _effect--ripple waves-effect waves-light">Update
                                                    Profile</button>
                                                <a href="{{route("admin.profile")}}" class="btn btn-outline-warning mb-2 me-4 _effect--ripple waves-effect waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                                        <polyline points="15 18 9 12 15 6"></polyline>
                                                    </svg> Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="change-password" class="tab-pane fade" role="tabpanel">
                                <form class="needs-validation mt-4" method="post" action="{{route("admin.update")}}" autocomplete="off" novalidate="novalidate">
                                    <div class="row">
                                        @csrf
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="current-password-input">Current Password</label>
                                                <input id="current-password-input" type="password" name="current_password" class="form-control" placeholder="Enter Current Password" autocomplete="off" required="required" maxlength="255" spellcheck="false">
                                                <div class="invalid-feedback">Please enter current password</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="new-password-input">New Password</label>
                                                <input id="new-password-input" type="password" name="new_password" class="form-control" placeholder="Enter New Password" autocomplete="off" required="required" maxlength="255" spellcheck="false">
                                                <div class="invalid-feedback">Please enter new password</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="confirm-new-password-input">Confirm New Password</label>
                                                <input id="confirm-new-password-input" type="text" name="c_new_password" class="form-control" placeholder="Re-enter New Password" autocomplete="off" required="required" maxlength="255" spellcheck="false">
                                                <div class="invalid-feedback">Please re-enter new password</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mt-3 mb-5 text-start">
                                                <button type="submit" class="btn btn-outline-info mb-2 me-4 _effect--ripple waves-effect waves-light">Update
                                                    Password</button>

                                                <a href="{{route("admin.profile")}}" class="btn btn-outline-warning mb-2 me-4 _effect--ripple waves-effect waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                                        <polyline points="15 18 9 12 15 6"></polyline>
                                                    </svg> Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-xl-12 col-lg-12 col-sm-12 text-end">
        <a href="{{route("admin.profile")}}" class="btn btn-primary px-5 mb-3 _effect--ripple"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
        <polyline points="15 18 9 12 15 6"></polyline>
    </svg> Back</a>
</div> --}}
</div>


@endsection
