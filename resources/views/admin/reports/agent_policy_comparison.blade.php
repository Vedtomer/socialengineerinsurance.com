@extends('admin.layouts.customer')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-balance-scale me-2"></i>Agent Policy Comparison</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reports.agent-policy-comparison') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="agent_ids" class="form-label">Select Agents to Compare</label>
                                        <select class="form-control select2" id="agent_ids" name="agent_ids[]" multiple="multiple">
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ in_array($agent->id, request('agent_ids', [])) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="period" class="form-label">Comparison Period</label>
                                        <select class="form-control" id="period" name="period">
                                            <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Compare
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Agent Name</th>
                                        <th>Current Period Policies</th>
                                        <th>Previous Period Policies</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($comparisonData as $data)
                                        <tr>
                                            <td>{{ $data['agent_name'] }}</td>
                                            <td>{{ $data['current_period_policies'] }}</td>
                                            <td>{{ $data['previous_period_policies'] }}</td>
                                            <td>{{ $data['difference'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
