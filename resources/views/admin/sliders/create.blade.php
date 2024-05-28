@extends('admin.layouts.app')

@section('content')

    


    <div class="layout-px-spacing">

        <div class="middle-content container-xxl p-0">
    
            <div class="col-lg-6 mx-auto mt-4">
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
                        <form action="{{ route('sliders.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name">Upload Slider Image</label>
                                <input type="file" class="form-control" name="image" required>
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
    
    </div>
@endsection
