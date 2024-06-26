@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Slider</a></li>
<li class="breadcrumb-item active" aria-current="page">Mobile App Slider</li>
@endsection



@section('content')


<div class="row ">
    <!-- <div class="col-lg-3 col-md-3 col-sm-3 mb-4">
                            <input id="t-text" type="text" name="txt" placeholder="Search" class="form-control" required="">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
                            <select class="form-select form-select" aria-label="Default select example">
                                <option selected="">All Category</option>
                                <option value="3">Apperal</option>
                                <option value="1">Electronics</option>
                                <option value="2">Clothing</option>
                                <option value="3">Accessories</option>
                                <option value="3">Organic</option>
                            </select>
                        </div> -->

    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary"><a id="openModalBtn" href="{{ route('sliders.create') }}">Add Slider</a></button>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    @forelse ($sliders as $slider)
    <div class="col-xxl-4 col-xl-4 col-lg-3 col-md-4 col-sm-6 mb-4">
        <a class="card style-6" href="#">
            <!-- <button class="btn btn-sm status-toggle clickable" data-id="{{ $slider->id }}" data-status="{{ $slider->status ? '1' : '0' }}" onclick="toggleStatus(this)"> -->
            @if ($slider->status)
            <span class="badge badge-success">Active</span>
            @else
            <span class="badge badge-danger">Inactive</span>
            @endif
            </button>
            <img src="{{ $slider->image }}" class="card-img-top" alt="...">
            <div class="card-footer">
                <div class="row">
                    <div class="col-3">
                        <!-- <div class="badge--group">
                                <div class="badge badge-primary badge-dot"></div>
                                <div class="badge badge-danger badge-dot"></div>
                                <div class="badge badge-info badge-dot"></div>
                                </div> -->
                    </div>
                    <div class="col-9 text-end">
                        <div class="pricing d-flex justify-content-end">
                            <p class="text-success mb-0">
                                <!-- Add delete icon with a form for deleting the slider -->
                                <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @empty
    <p>No sliders available.</p>
    @endforelse
</div>



@endsection
