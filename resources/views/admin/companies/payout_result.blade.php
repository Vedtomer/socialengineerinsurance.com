@if($period == 'annually')
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Net Amount</h5>
                    <h3 class="text-white">₹ {{ number_format(round($totalNetAmount), 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Payout</h5>
                    <h3 class="text-white">₹ {{ number_format(round($payout), 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-right">Total Net Amount</th>
                    <th class="text-right">Payout</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $data)
                    <tr>
                        <td>{{ $data['month_name'] }}</td>
                        <td class="text-right">₹ {{ number_format(round($data['net_amount']), 0) }}</td>
                        <td class="text-right">₹ {{ number_format(round($data['payout']), 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th class="text-right">₹ {{ number_format(round($totalNetAmount), 0) }}</th>
                    <th class="text-right">₹ {{ number_format(round($totalPayout), 0) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endif
