@extends('admin.layouts.app')
@section('title', 'Upload Multiple Policy PDF Files')

@section('breadcrumb')

<li class="breadcrumb-item"><a href="#">Policy</a></li>
<li class="breadcrumb-item active" aria-current="page">Upload Policy Pdf File</li>
@endsection

@section('content')



<div class="row layout-top-spacing">

    <div class="col-lg-6 mx-auto mt-4">

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

        <div class="main-card mb-3 card">
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
                <form method="POST" action="{{ route('admin.policy_pdf_upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Upload PDF</label>
                        <input type="file" class="form-control" name="files[]" id="excelFile" accept=".pdf" multiple>
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
