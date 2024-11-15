@extends('backend.layouts.app')

@section('title', 'Profile')

@section('header')
{{ __('Profile') }}
@endsection

@section('content')

<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Profile</h2>
    </div>
</div>

<ul class="nav nav-underline mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
            type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Update Profile
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password"
            type="button" role="tab" aria-controls="pills-password" aria-selected="false">Change Password
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-delete-tab" data-bs-toggle="pill" data-bs-target="#pills-delete"
            type="button" role="tab" aria-controls="pills-delete" aria-selected="false">Delete Account
        </button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
        tabindex="0">
        @include('profile.partials.update-profile-information-form')
    </div>
    <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
        <div class="card mt-4">
            <div class="card-header pb-0">
                <h6>{{ __('Update Password') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-delete" role="tabpanel" aria-labelledby="pills-delete-tab" tabindex="0">
        <div class="card mt-4">
            <div class="card-header pb-0">
                <h6>{{ __('Delete Account') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile._modal')

@endsection

@push('js')
<script src="{{ asset('assets/backend/library/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>

<script>
@if(session('status')) // Pastikan session('status') adalah string, bukan array let
statusMessage = @json(session('status'));
if (typeof statusMessage === 'object') {
    statusMessage = statusMessage.message || 'Unknown status';
}
toastSuccess(statusMessage);
@endif
</script>
@endpush