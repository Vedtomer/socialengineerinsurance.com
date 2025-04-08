@extends('admin.layouts.customer')
@section('title', 'Upload Multiple Policies')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Policy</a></li>
<li class="breadcrumb-item active" aria-current="page">Upload Policy</li>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto mt-4">
        <!-- Main upload card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Upload Daily Policy</h5>
            </div>
            <div class="card-body">
                <!-- Alert messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Form level errors -->
                @if($errors->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Validation errors -->
                @if($errors->any() && !$errors->has('error') && !$errors->has('import_errors'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Import specific errors -->
                @if($errors->has('import_errors'))
                <div class="alert alert-danger" role="alert">
                    <h6>Import Errors:</h6>
                    <ul class="mb-0 small">
                        @foreach($errors->get('import_errors') as $errorArray)
                            @foreach((array)$errorArray as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Upload form -->
                <form method="POST" action="{{ route('admin.upload') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Select Date of Policy <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" required value="{{ old('date') }}">
                        @error('date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Upload Excel <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                            <a href="/sample/sample-policy.xls" class="btn btn-outline-secondary" download>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="download-icon" viewBox="0 0 24 24">
                                    <path d="M5 20h14v-2H5v2zm7-18L5.33 9h4.67v4h4V9h4.67L12 2z"/>
                                  </svg>
                                   Sample Excel
                            </a>
                        </div>
                        <small class="text-muted">Accepted file types: .xls, .xlsx</small>
                        @error('excelFile')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Show upload stats if available -->
                    @if(session('stats'))
                    <div class="alert alert-success mt-3">
                        <h6>Upload Statistics:</h6>
                        <ul class="mb-0">
                            <li>Rows processed: {{ session('stats')['processed'] }}</li>
                            <li>New records: {{ session('stats')['created'] }}</li>
                            <li>Updated records: {{ session('stats')['updated'] }}</li>
                            <li>Skipped rows: {{ session('stats')['skipped'] }}</li>
                        </ul>
                    </div>
                    
                    @if(!empty(session('stats')['errors']))
                    <div class="alert alert-warning">
                        <h6>Row Errors:</h6>
                        <div class="small" style="max-height: 200px; overflow-y: auto;">
                            <ul class="mb-0">
                                @foreach(session('stats')['errors'] as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @endif

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-upload me-1"></i> Upload File
                        </button>
                        <a href="{{ route('agent.list') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Disable submit button on form submission to prevent double submission
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        });
        
        // File size validation
        document.getElementById('excelFile').addEventListener('change', function() {
            const fileInput = this;
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size;
                if (fileSize > maxSize) {
                    alert('File size exceeds 10MB. Please choose a smaller file.');
                    fileInput.value = '';
                }
            }
        });
    });
</script>
@endpush