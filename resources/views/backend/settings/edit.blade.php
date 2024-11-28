@extends('backend.layouts.app')

@section('title', 'Settings')

@push('css')

@endpush

@section('content')

<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Settings</h2>
    </div>
</div>

<div class="card shadow none-border">
    <div class="card-body">
        <header>
            <p class="mt-1 text-sm text-muted">
                <span class="badge badge-phoenix fs-9 badge-phoenix-warning">
                    <span class="badge-label">
                        {{ __("Update Your Setting Information") }}
                        <span class="ms-1" data-feather="alert-octagon">
                        </span>
                    </span>
                </span>
            </p>
        </header>
        <form action="" method="" enctype="multipart/form-data">
            <!-- Input Gambar -->
            <div class="text-center mb-4">
                <input type="file" class="d-none" id="image" name="image">
                <div class="hoverbox feed-profile" style="width: 150px; height: 150px">
                    <div class="hoverbox-content rounded-circle d-flex flex-center z-1"
                        style="--phoenix-bg-opacity: .56;">
                        <span class="fa-solid fa-camera fs-3 text-secondary-light"></span>
                    </div>
                    <div
                        class="position-relative bg-body-quaternary rounded-circle cursor-pointer d-flex flex-center mb-xxl-7">
                        <div class="avatar avatar-5xl">
                            <img class="rounded-circle rounded-circle img-thumbnail shadow-sm border-0"
                                src=" {{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/default.png')  }} "
                                alt="">
                        </div>
                        <label class="w-100 h-100 position-absolute z-1" for="upload-porfile-picture"></label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $settings->name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        value="{{ $settings->description }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control">{{ $settings->address }}</textarea>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@push('js')

@endpush