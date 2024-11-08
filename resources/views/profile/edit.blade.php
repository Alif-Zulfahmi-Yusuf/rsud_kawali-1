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

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
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
        <div class="card">
            <div class="card-header pb-0">
                <h6>{{ __('Profile Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>
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

@endsection