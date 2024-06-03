@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Reward</a></li>
<li class="breadcrumb-item active" aria-current="page">Policy Listing</li>
@endsection

@section('content')


<div class="row">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Agent</th>
                            <th>Point</th>
                            <th>TDS</th>
                            <th>Amount Paid</th>
                            <th>Policy Month</th>
                            <th>Updated Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($points as $point)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $point->agent->name }}</td>
                            <td>{{ $point->points }}</td>
                            <td>{{ $point->tds }}</td>
                            <td>{{ $point->amount_to_be_paid }}</td>
                            <td>
                                @if(!empty($point->policy_period_month_year))
                                    {{ \Carbon\Carbon::parse($point->policy_period_month_year)->format('F Y') }}
                                @else
                                    <!-- Optionally, you can display a default value or leave it empty -->
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($point->created_at)->isoFormat('MMM DD, YYYY h:mm A') }}
                            </td>

                            <td><span class="badge badge-{{ $point->status == 'completed' ? 'success' : ($point->status == 'rejected' ? 'danger' : 'secondary') }} title-case">{{ $point->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>


@endsection
