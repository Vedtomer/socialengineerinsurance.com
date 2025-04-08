@extends('admin.layouts.customer')
@section('title', 'Upload Policies')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Policy</a></li>
<li class="breadcrumb-item active" aria-current="page">Upload Policy</li>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-12 mx-auto mt-4">
        <!-- Main container card -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h4 class="card-title mb-0">Policy Management</h4>
            </div>
            <div class="card-body">
                <!-- Alert messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Tab navigation -->
                <ul class="nav nav-tabs mb-4" id="policyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel-upload" type="button" role="tab" aria-controls="excel-upload" aria-selected="true">
                            <i class="fas fa-file-excel me-2"></i>Excel Upload
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pdf-tab" data-bs-toggle="tab" data-bs-target="#pdf-upload" type="button" role="tab" aria-controls="pdf-upload" aria-selected="false">
                            <i class="fas fa-file-pdf me-2"></i>PDF Upload
                        </button>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="policyTabsContent">
                    <!-- Excel Upload Tab -->
                    <div class="tab-pane fade show active" id="excel-upload" role="tabpanel" aria-labelledby="excel-tab">
                        <div class="card border">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-upload me-2"></i>Upload Daily Policy Excel</h5>
                            </div>
                            <div class="card-body">
                                <!-- Form level errors for Excel -->
                                @if($errors->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                <!-- Validation errors for Excel -->
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

                                <!-- Excel Upload form -->
                                <form method="POST" action="{{ route('admin.upload') }}" enctype="multipart/form-data" id="uploadForm">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Select Date of Policy <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required value="{{ old('date') }}">
                                            @error('date')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Upload Excel <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                                                <a href="/sample/sample-policy.xls" class="btn btn-outline-secondary" download>
                                                    <i class="fas fa-download me-1"></i> Sample Excel
                                                </a>
                                            </div>
                                            <small class="text-muted">Accepted file types: .xls, .xlsx (Max: 10MB)</small>
                                            @error('excelFile')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Show upload stats if available -->
                                    @if(session('stats'))
                                    <div class="alert alert-success mt-3">
                                        <h6>Upload Statistics:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="mb-0">
                                                    <li>Rows processed: {{ session('stats')['processed'] }}</li>
                                                    <li>New records: {{ session('stats')['created'] }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="mb-0">
                                                    <li>Updated records: {{ session('stats')['updated'] }}</li>
                                                    <li>Skipped rows: {{ session('stats')['skipped'] }}</li>
                                                </ul>
                                            </div>
                                        </div>
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
                                            <i class="fas fa-upload me-1"></i> Upload Excel
                                        </button>
                                        <a href="{{ route('agent.list') }}" class="btn btn-secondary ms-2">
                                            <i class="fas fa-arrow-left me-1"></i> Back
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Upload Tab -->
                    <div class="tab-pane fade" id="pdf-upload" role="tabpanel" aria-labelledby="pdf-tab">
                        <div class="card border">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-file-pdf me-2"></i>Upload Policy PDF Files</h5>
                            </div>
                            <div class="card-body">
                                <!-- Show successful uploads -->
                                @if(!empty($successFiles))
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-check-circle me-1"></i> Uploaded Successfully:</h6>
                                    <ul class="mb-0">
                                        @foreach($successFiles as $file)
                                        <li>{{ $file }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Show failed uploads -->
                                @if(!empty($failedFiles))
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-circle me-1"></i> Failed to Upload:</h6>
                                    <ul class="mb-0">
                                        @foreach($failedFiles as $file => $message)
                                        <li>{{ $file }} - {{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- PDF Upload form -->
                                <form method="POST" action="{{ route('admin.policy_pdf_upload') }}" enctype="multipart/form-data" id="pdfUploadForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label">Upload PDF Files <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" name="files[]" id="pdfFiles" accept=".pdf" multiple>
                                        </div>
                                        <small class="text-muted">You can select multiple PDF files</small>
                                        @error('files.*')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary" id="pdfSubmitBtn">
                                            <i class="fas fa-upload me-1"></i> Upload PDF Files
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preserve active tab after form submission
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`[data-bs-target="${hash}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }

        // Disable Excel submit button on form submission
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        });
        
        // Disable PDF submit button on form submission
        document.getElementById('pdfUploadForm').addEventListener('submit', function() {
            document.getElementById('pdfSubmitBtn').disabled = true;
            document.getElementById('pdfSubmitBtn').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        });
        
        // Excel file size validation
        document.getElementById('excelFile').addEventListener('change', function() {
            validateFileSize(this, 10);
        });
        
        // PDF file size validation
        document.getElementById('pdfFiles').addEventListener('change', function() {
            for (let i = 0; i < this.files.length; i++) {
                validateFileSize(this, 15, i);
            }
        });
        
        // File size validation function
        function validateFileSize(fileInput, maxSizeMB, fileIndex = 0) {
            const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes
            
            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[fileIndex].size;
                const fileName = fileInput.files[fileIndex].name;
                
                if (fileSize > maxSize) {
                    alert(`File "${fileName}" exceeds ${maxSizeMB}MB. Please choose a smaller file.`);
                    fileInput.value = '';
                    return false;
                }
            }
            return true;
        }
    });
</script>
@endpush