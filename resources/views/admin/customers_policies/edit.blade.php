@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Customer</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer-policies.index') }}">Customer Policies</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Customer Policy</li>
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
                    <form class="row g-3" action="{{ route('customer-policies.update', $customerPolicy->id) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="user_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="select2 form-select customer" id="user_id" name="user_id"
                                aria-label="Select Customer" required>
                                <option value="">Select Customer</option>
                                @foreach (getCustomers() as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('user_id', $customerPolicy->user_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="policy_no" class="form-label">Policy No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="policy_no" name="policy_no"
                                value="{{ old('policy_no', $customerPolicy->policy_no) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="policy_start_date" class="form-label">Start Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="policy_start_date" name="policy_start_date"
                                value="{{ old('policy_start_date', $customerPolicy->policy_start_date) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="policy_end_date" class="form-label">End Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="policy_end_date" name="policy_end_date"
                                value="{{ old('policy_end_date', $customerPolicy->policy_end_date) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active"
                                    {{ old('status', $customerPolicy->status) == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="expired"
                                    {{ old('status', $customerPolicy->status) == 'expired' ? 'selected' : '' }}>Expired
                                </option>
                                <option value="cancelled"
                                    {{ old('status', $customerPolicy->status) == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="pending"
                                    {{ old('status', $customerPolicy->status) == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved"
                                    {{ old('status', $customerPolicy->status) == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="net_amount" class="form-label">Net Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="net_amount" name="net_amount"
                                value="{{ old('net_amount', $customerPolicy->net_amount) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="gst" class="form-label">GST Amount<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="gst" name="gst"
                                value="{{ old('gst', $customerPolicy->gst) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="premium" class="form-label">Premium <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="premium" name="premium"
                                value="{{ old('premium', $customerPolicy->premium) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="insurance_company" class="form-label">Insurance Company <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="insurance_company" name="insurance_company"
                                value="{{ old('insurance_company', $customerPolicy->insurance_company) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="policy_type" class="form-label">Policy Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="policy_type" name="policy_type" required>
                                <option value="life_insurance"
                                    {{ old('policy_type', $customerPolicy->policy_type) == 'life_insurance' ? 'selected' : '' }}>
                                    Life Insurance</option>
                                <option value="health_insurance"
                                    {{ old('policy_type', $customerPolicy->policy_type) == 'health_insurance' ? 'selected' : '' }}>
                                    Health Insurance</option>
                                <option value="general_insurance"
                                    {{ old('policy_type', $customerPolicy->policy_type) == 'general_insurance' ? 'selected' : '' }}>
                                    General Insurance</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="product_id" class="form-label">Insurance Product <span
                                    class="text-danger">*</span></label>
                            <select class="select2 form-select InsuranceProduct" id="product_id" name="product_id"
                                aria-label="Select Insurance Product" required>
                                <option value="" disabled>Select Insurance Product</option>
                                @foreach (getInsuranceProducts() as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id', $customerPolicy->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="policy_document" class="form-label">Upload Policy Document</label>
                            <input type="file" class="form-control" id="policy_document" name="policy_document"
                                accept="application/pdf">
                            <small class="text-muted">Upload PDF file only.</small>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('customer-policies.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
