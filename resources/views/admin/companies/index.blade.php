@extends('admin.layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Company</a></li>
<li class="breadcrumb-item active" aria-current="page">Insurance Company</li>
@endsection


@section('content')


<div class="row ">
   

    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-4 ms-auto">
        <div class="action-btn layout-top-spacing">
            <button id="add-list" class="btn btn-secondary"><a id="openModalBtn" href="{{ route('companies.create') }}">Add Company</a></button>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    






    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Logo</th>
                    <th scope="col">Name</th>
                    <th class="text-center" scope="col">Status</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $slider)
                <tr>
                    
                    <td>
                        <div class="media">
                            <div class="avatar me-2">
                                <img alt="avatar" src="{{$slider->image}}" class="rounded-circle" />
                            </div>
                           
                        </div>
                    </td>
                    <td>
                        {{$slider->name}}
                        
                    </td>
                    <td class="text-center">
                        @if($slider->status===1)
                        <span class="badge badge-light-success">Actvie</span>
                        @else
                        <span class="badge badge-light-danger">Inactive</span>
                        @endif
                    </td>
                    
                </tr>

                @empty
                <p>No Company added.</p>
                @endforelse

                
            </tbody>
        </table>
    </div>
</div>





@endsection
