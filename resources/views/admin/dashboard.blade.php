@extends('admin.layouts.app')

{{-- @section('title', 'Home') --}}

@section('content')
<div class="layout-px-spacing" style="min-height: 0px !important;">

    <div class="middle-content container-xxl p-0">

        <div class="row ">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-card-four h-100">
                    <div class="widget-content">
                        <div class="w-header">
                            <div class="w-info">
                                <h6 class="value">Premium</h6>
                            </div>

                        </div>

                        <div class="w-content">

                            <div class="w-info">
                                <p class="value">₹ {{$data['premiums']}}

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                        <polyline points="17 6 23 6 23 12"></polyline>
                                    </svg></p>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-card-four h-100">
                    <div class="widget-content">
                        <div class="w-header">
                            <div class="w-info">
                                <h6 class="value">Policy</h6>
                            </div>

                        </div>

                        <div class="w-content">

                            <div class="w-info">
                                <p class="value">⚡ {{$data['policyCount']}}

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                        <polyline points="17 6 23 6 23 12"></polyline>
                                    </svg></p>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-card-five">
                    <div class="widget-content">
                        <div class="account-box">

                            <div class="info-box">
                                <div class="icon">
                                    <span>
                                        <img src="{{ asset('asset/admin/images/png/money-bag.png') }}" alt="money-bag">
                                    </span>
                                </div>

                                <div class="balance-info">
                                    <h6>Total Balance</h6>
                                    <p>₹{{ $data['paymentby']}}</p>
                                </div>
                            </div>

                            <div class="card-bottom-section">
                                <div><span class="badge badge-light-success"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg></span></div>
                                <a href="{{ route('agentpandding.blance') }}" class="">View Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="layout-px-spacing" style="min-height: 0px !important;">

    <div class="middle-content container-xxl p-0">

        <div class="row ">

            @forelse ($data['companies'] as $slider)
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 ">
                <div class="user-profile">
                    <div class="widget-content widget-content-area">

                        <div class="text-center user-info">
                            <img src="{{$slider->image}}" alt="avatar" width="100px" height="100px" class="mt-4">
                            <p class="">{{$slider->name}}</p>
                        </div>
                        <div class="user-info-list">

                            <div class="">
                                <ul class="contacts-block list-unstyled">
                                    <li class="contacts-block__item">
                                        Premium: <span class="info">₹ {{$slider->total_premium}} </span>
                                    </li>
                                    <li class="contacts-block__item">
                                        Policy: <span class="w-info"> {{$slider->total_policies}}</span>
                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty

            @endforelse


        </div>
    </div>
</div>

@endsection
