@extends('admin.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Commission</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manage Agent Commission</li>
@endsection

@section('content')
    <div class="col-lg-6">
        <div class="main-card mb-3 card">
            <div class="card-body">

                <form action="{{ $data ? route('agent.commission', ['id' => $data->id]) : '#' }}" method="POST"
                    id="fix">
                    @csrf
                    <input type="hidden" class="form-control" name="id">

                    <div class="add" style="display: flex; align-items: center;">
                        <div class="btns" style="margin-left: auto;">

                            <a href="#" class="action-btn btn-delete bs-tooltip" data-toggle="tooltip"
                                data-placement="top" aria-label="Add" data-bs-original-title="Add" id="addCommissionBtn"
                                style="color: green">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </a>

                            <a href="#" class="action-btn btn-delete bs-tooltip danger" data-toggle="tooltip"
                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete"
                                id="removeCommissionBtn" style="color: #f8538d">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @forelse ($commissiondata as $record)
                        <div class="row mb-4 commission-container" id="commissionContainer">
                            <input type="hidden" class="form-control" name="id[]" value="{{ $record->id }}" required>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Enter Commission"
                                    name="commission[]" value="{{ $record->commission }}" required>
                            </div>
                            <div class="col">
                                <select class="form-control form-control" required name="commission_type[]">
                                    <option selected disabled>Select Commission Type</option>
                                    <option value="fixed" {{ $record->commission_type === 'fixed' ? 'selected' : '' }}>
                                        Fixed</option>
                                    <option value="percentage"
                                        {{ $record->commission_type === 'percentage' ? 'selected' : '' }}>Percentage
                                    </option>
                                </select>
                            </div>
                            <!-- Exclude delete button from being cloned -->
                            <div class="col-md-2 d-flex align-items-center">
                                <a href="{{ route('delete.commission', ['id' => $record->id]) }}" class="text-danger"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-x-circle table-cancel">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg></a>
                            </div>
                        </div>
                    @empty
                        <div class="row mb-4 commission-container" id="commissionContainer">
                            <div class="col">
                                <input type="number" class="form-control" placeholder="Enter Commission"
                                    name="commission[]" required>
                            </div>
                            <div class="col">
                                <select class="form-control form-control" required name="commission_type[]">
                                    <option selected disabled>Select Commission Type</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                        </div>
                    @endforelse


                </form>
            </div>
            <div class="d-flex justify-content-center pb-5">
                <button type="submit" class="btn btn-primary" onclick="submitForm(fix)">Submit</button>
                <a href="{{ route('agent.list') }}" class="btn btn-secondary mx-2 ml-2">Back</a>
            </div>
        </div>
    </div>
@endsection
