@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Claim</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Insurance Claim</li>
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
                    <form class="row g-3" action="{{ route('claims.store') }}" method="post" autocomplete="off">
                        @csrf



                        <div class="col-md-12">
                            <label for="policy_number" class="form-label">Policy Number <span
                                    class="text-danger">*</span></label>
                            <a href="{{ route('claims.create') }}?policy-number=1" class="text-primary ms-2">Click here if
                                not found policy</a>

                            @if (empty($_GET['policy-number']))
                                <input type="hidden" id="users_id" name="users_id">
                                <select class="select2 form-select Policy_Number" id="policy_number" name="policy_number"
                                    aria-label="Default select example" required>
                                    <option value=""></option>
                                    @foreach (getPolicy() as $item)
                                        <option value="{{ $item['policy_no'] }}"
                                            data-customername="{{ $item['customername'] }}"
                                            data-agent_name="{{ $item['agent_name'] }}"
                                            data-users_id="{{ $item['agent_id'] }}">
                                            {{ $item['policy_no'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" id="policy_number" name="policy_number"
                                    value="{{ old('policy_number') }}">
                            @endif
                        </div>




                        @if (empty($_GET['policy-number']))
                            <div class="col-md-6">
                                <label for="agent" class="form-label">Agent</label>
                                <input type="text" class="form-control" id="agent_name" name="agent_name"
                                    value="{{ old('agent_name') }}" readonly>
                            </div>
                        @else
                            <div class="col-md-6">
                                <label for="policy_number" class="form-label">Select Agent <span
                                        class="text-danger">*</span></label>

                                <select class="select2 form-select js-example-basic-single" id="policy_number"
                                    name="users_id" aria-label="Default select example" required>
                                    <option value=""></option>
                                    @foreach (getAgents() as $item)
                                        <option value="{{ $item['id'] }}">
                                            {{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        @endif

                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                value="{{ old('customer_name') }}" @if (empty($_GET['policy-number'])) readonly @endif>
                        </div>
                        <div class="col-md-12">
                            <label for="claim_number" class="form-label">Claim Number</label>
                            <input type="text" class="form-control" id="claim_number" name="claim_number"
                                value="{{ old('claim_number') }}" required>
                        </div>


                        <div class="col-6">
                            <label for="claim_date" class="form-label">Claim Date</label>
                            <input type="date" class="form-control" id="claim_date" name="claim_date"
                                value="{{ old('claim_date') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="incident_date" class="form-label">Incident Date</label>
                            <input type="date" class="form-control" id="incident_date" name="incident_date"
                                value="{{ old('incident_date') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="amount_claimed" class="form-label">Amount Claimed</label>
                            <input type="number" class="form-control" id="amount_claimed" name="amount_claimed"
                                value="{{ old('amount_claimed') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="amount_approved" class="form-label">Amount Approved</label>
                            <input type="number" class="form-control" id="amount_approved" name="amount_approved"
                                value="{{ old('amount_approved') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="in_review" {{ old('status') == 'in_review' ? 'selected' : '' }}>In Review
                                </option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="awaiting_documents"
                                    {{ old('status') == 'awaiting_documents' ? 'selected' : '' }}>Awaiting Documents
                                </option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="escalated" {{ old('status') == 'escalated' ? 'selected' : '' }}>Escalated
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('claims.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
