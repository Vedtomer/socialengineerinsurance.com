@extends('admin.layouts.app')
@section('title', 'Upload Multiple Policy PDF Files')
@section('section')

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

    <div class="container">
        <div class="col-lg-6 d-flex mx-auto">
            <div class="main-card mb-3 card mx-auto">
                <div class="card-body">
                    <div class="add" style="display: flex; align-items: center;">
                        <div class="btns" style="margin-left: auto;"></div>
                    </div>
        
                    <form method="POST" action="{{ route('admin.policy_pdf_upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload PDF</label>
                            <input type="file" class="form-control" name="files[]" id="excelFile" accept=".pdf" multiple>
                            <small class="text-muted">Accepted file types: PDF</small>
                            @error('excelFile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    
                
                </div>
            </div>
        </div>
    </div>

@endsection
