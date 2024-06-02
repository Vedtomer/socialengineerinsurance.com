@extends('admin.layouts.app')


@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Commission</a></li>
<li class="breadcrumb-item active" aria-current="page">Manage Agent Commission</li>
@endsection

@section('content')
<div class="col-lg-6">
    <div class="main-card mb-3 card">
        <div class="card-body">

            <form action="{{ $data ? route('agent.commission', ['id' => $data->id]) : '#' }}" method="POST" id="fix">
                @csrf
                <input type="hidden" class="form-control" name="id">

                <div class="add" style="display: flex; align-items: center;">
                    <div class="btns" style="margin-left: auto;">
                        <button id="addCommissionBtn" class="btn btn-success"><i class="fa fa-plus" style="font-size:16px"></i></button>
                        <button id="removeCommissionBtn" class="btn btn-danger"><i class="fa fa-minus" style="font-size:16px"></i></button>
                    </div>
                </div>

                @forelse ($commissiondata as $record)
                    <div class="row mb-4" id="commissionContainer">
                        <input type="hidden" class="form-control" name="id[]" value="{{ $record->id }}" required>
                        <div class="col">
                            <input type="number" class="form-control" placeholder="Enter Commission" name="commission[]" value="{{ $record->commission }}"  required>
                        </div>
                        <div class="col">
                            <select class="form-control form-control" required name="commission_type[]"> 
                                <option selected disabled >Select Commission Type</option>
                                <option value="fixed" {{ $record->commission_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="percentage" {{ $record->commission_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                              
                            </select>
                        </div>
                    </div>
                    <!-- Exclude delete button from being cloned -->
                    <div class="col-md-2 d-flex align-items-center">
                        <a href="{{ route('delete.commission', ['id' => $record->id]) }}" class="text-danger">Delete</a>
                    </div>
               
                @empty


                <div class="row mb-4" id="commissionContainer">
                    <div class="col">
                        <input type="number" class="form-control" placeholder="Enter Commission" name="commission[]" required>
                    </div>
                    <div class="col">
                        <select class="form-control form-control" required name="commission_type[]"> 
                            <option selected disabled >Select Commission Type</option>
                            <option value="fixed">Fixed</option>
                            <option value="percentage">Percentage</option>
                          
                        </select>
                    </div>
                </div>
               
                @endforelse


                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(fix)">Submit</button>
                    <a href="{{ route('agent.list') }}" class="btn btn-secondary mx-2 ml-2">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
