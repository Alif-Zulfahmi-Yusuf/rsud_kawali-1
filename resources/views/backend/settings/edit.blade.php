@extends('backend.layouts.app')

@section('title', 'Settings')

@push('css')
<!-- Tambahkan custom CSS jika perlu -->
@endpush

@section('content')

<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Settings</h2>
    </div>
</div>
<!-- Header informasi -->
<header>
    <p class="mt-1 text-sm text-muted">{{ __("Update Your Setting Information.") }}
        <span class="ms-1" data-feather="alert-octagon"></span>
    </p>
</header>

<div class="card shadow-none border-0">
    <div class="card-body">

        <!-- Form untuk update setting -->
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <!-- Input Gambar -->
            <div class="text-center mb-4">
                <img src="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}"
                    alt="Web Logo" class="img-fluid" width="150" height="150" style="object-fit: cover">
                <div class="mt-2">
                    <input type="file" class="form-control d-inline-block" id="image" name="image" style="width: auto;">
                    @error('image')
                    <div class="text-danger">
                        <span class="badge badge-phoenix fs-10 badge-phoenix-danger">
                            {{ $message }}
                        </span>
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Input Data Lain -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $settings->name }}"
                            required="">
                        @error('name')
                        <div class="text-danger">
                            <span class="badge badge-phoenix fs-10 badge-phoenix-danger">
                                {{ $message }}
                            </span>
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description"
                            value="{{ $settings->description }}" required="">
                        @error('description')
                        <div class="text-danger">
                            <span class="badge badge-phoenix fs-10 badge-phoenix-danger">
                                {{ $message }}
                            </span>
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class=" row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="4"
                            required="">{{ $settings->address }}</textarea>
                        @error('address')
                        <div class="text-danger">
                            <span class="badge badge-phoenix fs-10 badge-phoenix-danger">
                                {{ $message }}
                            </span>
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Button Submit -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}"); // Menampilkan error toast jika ada
@endif
</script>
@endpush