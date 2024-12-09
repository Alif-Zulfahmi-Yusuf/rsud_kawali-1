@extends('backend.layouts.app')

@section('title', 'edit')


@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Perilaku</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-6">
            <div class="card-body">
                <form action="{{ route('perilaku.update', $perilaku->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="uuid" value="{{ $perilaku->uuid }}">
                    <div class="form-group mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="edit-category" name="category_perilaku_id" required>
                            <option value="" selected disabled>Pilih Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $perilaku->category_perilaku_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="perilaku" class="form-label">Perilaku</label>
                        <input type="text" name="name" id="edit-perilaku" value="{{ $perilaku->name }}"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary">Save</button>
                    <a href="{{ route('perilaku.index') }}" class="btn btn-outline-danger">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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