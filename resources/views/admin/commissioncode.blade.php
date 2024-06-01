@extends('admin.layouts.app')



@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Agent</a></li>
<li class="breadcrumb-item active" aria-current="page">Commission code</li>
@endsection

@section('content')

{{-- <div class="col-lg-12">
    <div class="main-card mb-3 mt-3 card">
        <div class="card-body">

          
         

            <div class="add" style="display: flex; align-items: center;">
               
                <div class="btns" style="margin-left: auto;">
                    <a id="openModalBtn" href="{{ route('agent') }}" class="btn btn-secondary mb-2">Add Agent</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="mb-0 table table-striped table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th style="width: 5%" scope="col">Sr. No.</th>
                            <th style="width: 5%" scope="col">Commission Code</th>
                            <th style="width: 20%" scope="col">Name</th>
                            
                           
                            <th style="width: 20%" scope="col">Email</th>
                            
                            <th style="width: 20%" scope="col">Mobile</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                            <tr @if ($user->commissions->isEmpty() || $user->commissions->sum('commission') == 0) style="background-color: #374181 ;" @endif >
                                <td>{{ $key + 1 }}</td>
                                <td style=" ">
                                    @foreach ($user->commissions as $commission)
                                        <div style="display: inline-block; margin-right: 10px; white-space: nowrap;">
                                            <button class="btn btn-secondary waves-effect waves-light"
                                                onclick="copyCommissionCode('{{ $commission['commission_code'] }}')">
                                                {{ $commission['commission_code'] }}
                                            </button>
                                            <span class="badge badge-warning">{{ $commission['commission'] }}
                                                {!! $commission->commission_type == 'percentage' ? '%' : '&#x20B9;' !!}</span>
                                        </div>
                                        <hr>
                                    @endforeach
                                </td>
                                <td>{{ $user->name }}</td>
                                
                                
                                <td>{{ $user->email }}</td>
                               
                                <td>{{ $user->mobile_number }}</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> --}}




<div class="row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Commission Code</th>
                            <th>Name</th>
                            <th>Mobile</th>
            
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style=" ">
                                @foreach ($user->commissions as $commission)
                                    <div style="display: inline-block; margin-right: 10px; white-space: nowrap;">
                                        <button class="btn btn-secondary waves-effect waves-light"
                                            onclick="copyCommissionCode('{{ $commission['commission_code'] }}')">
                                            {{ $commission['commission_code'] }}
                                        </button>
                                        <span class="badge badge-warning">{{ $commission['commission'] }}
                                            {!! $commission->commission_type == 'percentage' ? '%' : '&#x20B9;' !!}</span>
                                    </div>
                                    <hr>
                                @endforeach
                            </td>
                            <td>{{ $user->name }}</td>
                           
                          
                            <td>{{ $user->mobile_number }}</td>
                          

                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

