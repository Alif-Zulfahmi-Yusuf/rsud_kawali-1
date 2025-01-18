@extends('backend.layouts.app')

@section('title', 'Edit Category')

@section('content')

<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Edit Category</h2>
    </div>
</div>

<div class="card shadow border rounded-lg mb-4">
    <div class="card-body">
        <form action="{{ route('category.update', $categories->uuid) }}" method="POST" class="needs-validation"
            novalidate="">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $categories->name }}" required>
            </div>
            <button type="submit" class="btn btn-outline-secondary">Update</button>
            <a href="{{ route('category.index') }}" class="btn btn-outline-danger">Cancel</a>
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
toastError({
    errors: {
        message: "{{ session('error') }}"
    }
});
@endif
</script>
@endpush