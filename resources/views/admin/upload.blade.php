@extends('admin.layouts.app')
@section('title', 'Upload Multiple Policies')

@section('breadcrumb')

<li class="breadcrumb-item"><a href="#">Policy</a></li>
<li class="breadcrumb-item active" aria-current="page">Upload Policy</li>
@endsection

@section('content')



<div class="row layout-top-spacing">

    <div class="d-flex gap-3 m-3 mx-auto mt-4">

        @if(session('success'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger mt-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div>
            @if(!empty($successFiles))
            <h3>Uploaded Successfully:</h3>
            <ul>
                @foreach($successFiles as $file)
                <li>{{ $file }}</li>
                @endforeach
            </ul>
            @endif

            @if(!empty($failedFiles))
            <h3>Failed to Upload:</h3>
            <ul>
                @foreach($failedFiles as $file => $message)
                <li>{{ $file }} - {{ $message }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="main-card col-lg-6  mb-3 card">
            <div class="card-body">
                <!-- <div class="errors">
                        @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                        @endforeach
                        @endif
                    </div> -->
                <form method="POST" action="{{ route('admin.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <h6 for="name">Upload Daily Policy</h6>
                        <label>You don't need to add Policy date col in excel</label>
                        <div class="mb-3">
                            <label class="form-label">Select Date of Policy</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Excel <a href="/sample/sample-policy.xls" download style="color:blue">Download
                                    sample file</a></label>
                            <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                            <small class="text-muted">Accepted file types: .xls, .xlsx</small>
                            @error('excelFile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
            
        </div>
        <div class="main-card col-lg-6 mb-3 card mr-5">
            <div class="card-body">
                <!-- <div class="errors">
                        @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                        @endforeach
                        @endif
                    </div> -->
                <form method="POST" action="{{ route('admin.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <h6 for="name">Upload Monthly Policy</h6>
                        <label>You need to add Policy date col in excel</label>

                        <div class="mb-3">
                            <label class="form-label">Upload Excel <a href="/sample/bulk-sample-policy .xls" download>Download
                                    sample file</a></label>
                            <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                            <small class="text-muted">Accepted file types: .xls, .xlsx</small>
                            @error('excelFile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>                  
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('agent.list') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
            
        </div>
    </div>



</div>

@endsection
