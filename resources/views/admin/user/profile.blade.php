@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Profile</a></li>
<li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection

@section('content')


<div class="row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="widget user-profile">
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-11 mx-auto pb-4 mt-5">
                            <div class="text-center mb-2">
                                <span class="d-inline-block rounded-circle p-4 miwh-70p bg-primary">HA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-5">
                                <h3>Head Administrator</h3>
                                <a href="https://dcenergies.in/admin/profile/edit" class="mt-2 edit-profile" title="Edit Profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">Full Name</div>
                                    <div class="col-9 text-end text-info">{{$user->name}}</div>
                                </div>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">Role</div>
                                    <div class="col-9 text-end text-info text-uppercase">{{ Auth::user()->roles->pluck('name')[0] ?? '' }}</div>
                                </div>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">Email Address</div>
                                    <div class="col-9 text-end text-info">{{$user->email}}</div>
                                </div>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">Mobile Number</div>
                                    <div class="col-9 text-end text-info">{{$user->mobile_number}}</div>
                                </div>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">User Since</div>
                                    <div class="col-9 text-end text-info">{{$user->created_at}}</div>
                                </div>
                            </div>
                            <div class="details-section">
                                <div class="row">
                                    <div class="col-3">Last updated</div>
                                    <div class="col-9 text-end text-info">
                                        {{$user->updated_at}}
                                    </div>
                                </div>
                            </div>
                            
                        </div>


                        <div class="user-info-list">
                            <div>
                                <ul class="list-inline mt-5">
                                    <li class="list-inline-item mb-0 mx-3">
                                        <a href="https://dcenergies.in/admin/profile/edit" class="btn btn-info btn-icon btn-rounded _effect--ripple" title="Edit Profile">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


@endsection
