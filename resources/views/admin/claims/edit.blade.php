@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Claim</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Insurance Claim</li>
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
                    <form class="row g-3" action="{{ route('claims.update', $claim->id) }}" method="post" autocomplete="off">
                        @csrf
                        @method('PUT') <!-- Use PUT method for update -->

                        <input type="hidden" id="users_id" name="users_id" value="{{ $claim->users_id }}">
                        <div class="col-md-12">
                            <label for="policy_number" class="form-label">Policy Number <span
                                    class="text-danger">*</span></label>
                            <select class="select2 form-select Policy_Number" id="policy_number" name="policy_number"
                                aria-label="Default select example" required>
                                <option value=""></option>
                                @foreach (getPolicy() as $item)
                                    <option value="{{ $item['policy_no'] }}" data-customername="{{ $item['customername'] }}"
                                        data-agent_name="{{ $item['agent_name'] }}" data-users_id="{{ $item['agent_id'] }}"
                                        {{ $item['policy_no'] == $claim->policy_number ? 'selected' : '' }}>
                                        {{ $item['policy_no'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="agent" class="form-label">Agent</label>
                            <input type="text" class="form-control" id="agent_name" name="agent_name"
                                value="{{ $claim->agent_name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                value="{{ $claim->customer_name }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="claim_number" class="form-label">Claim Number</label>
                            <input type="text" class="form-control" id="claim_number" name="claim_number"
                                value="{{ $claim->claim_number }}" required>
                        </div>


                        <div class="col-6">
                            <label for="claim_date" class="form-label">Claim Date</label>
                            <input type="date" class="form-control" id="claim_date" name="claim_date"
                                value="{{ $claim->claim_date }}" required>
                        </div>
                        <div class="col-6">
                            <label for="incident_date" class="form-label">Incident Date</label>
                            <input type="date" class="form-control" id="incident_date" name="incident_date"
                                value="{{ $claim->incident_date }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="amount_claimed" class="form-label">Amount Claimed</label>
                            <input type="number" class="form-control" id="amount_claimed" name="amount_claimed"
                                value="{{ $claim->amount_claimed }}">
                        </div>
                        <div class="col-md-6">
                            <label for="amount_approved" class="form-label">Amount Approved</label>
                            <input type="number" class="form-control" id="amount_approved" name="amount_approved"
                                value="{{ $claim->amount_approved }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ $claim->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $claim->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $claim->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="in_review" {{ $claim->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                <option value="closed" {{ $claim->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="awaiting_documents" {{ $claim->status == 'awaiting_documents' ? 'selected' : '' }}>Awaiting Documents</option>
                                <option value="paid" {{ $claim->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="escalated" {{ $claim->status == 'escalated' ? 'selected' : '' }}>Escalated</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('claims.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
