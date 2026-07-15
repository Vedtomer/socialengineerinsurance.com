@extends('admin.layouts.customer')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
<li class="breadcrumb-item active" aria-current="page">Payout</li>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
        <div class="widget widget-content-area br-4">
            <div class="widget-one p-4">
                <h5 class="mb-4">Payout Calculation - {{ $company->name }}</h5>
                
                <form id="payoutForm">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period"><b>Period</b></label>
                                <select id="period" class="form-control" name="period">
                                    <option value="annually">Annually</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="financial_year"><b>Financial Year</b></label>
                                <select id="financial_year" class="form-control" name="financial_year">
                                    @php
                                        $currentMonth = date('m');
                                        $currentYear = $currentMonth > 3 ? date('Y') : date('Y') - 1;
                                    @endphp
                                    @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                                        <option value="{{ $i }}-{{ $i+1 }}">{{ $i }}-{{ $i+1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="percentage"><b>Percentage (%)</b></label>
                                <input type="number" id="percentage" class="form-control" name="percentage" value="59" step="0.01">
                            </div>
                        </div>
                    </div>
                </form>

                <div id="payoutResult" class="mt-4">
                    <!-- Results will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function fetchPayout() {
            var period = $('#period').val();
            var financial_year = $('#financial_year').val();
            var percentage = $('#percentage').val();

            $.ajax({
                url: '{{ route("companies.payout.data", $company->id) }}',
                type: 'GET',
                data: {
                    period: period,
                    financial_year: financial_year,
                    percentage: percentage
                },
                success: function(response) {
                    $('#payoutResult').html(response);
                },
                error: function() {
                    $('#payoutResult').html('<p class="text-danger">Failed to load data.</p>');
                }
            });
        }

        $('#period, #financial_year, #percentage').on('change input', function() {
            fetchPayout();
        });

        fetchPayout();
    });
</script>
@endsection
