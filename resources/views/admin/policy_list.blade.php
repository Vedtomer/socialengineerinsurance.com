@extends('admin.layouts.customer')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Agent</a></li>
            <li class="breadcrumb-item active" aria-current="page">Policy List</li>
        </ol>
    </nav>
@endsection

@section('content')
    <!-- Analytics Dashboard Section -->
    <div class="row g-4">
        @include('admin.policy.extra')

        <!-- Policy List Table -->
        @include("admin.policy.PolicyListTable")
    </div>

    <!-- View Details Modal -->
    @include('admin.policy.view_details_modal')
@endsection

@push('scripts')
@include("admin.policy.script")
@endpush