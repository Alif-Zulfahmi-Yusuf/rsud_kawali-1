@extends('backend.layouts.app')

@section('title', 'edit')



@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Pangkat</h2>
        </div>
        <div class="pull-right">

        </div>
    </div>
</div>

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Pangkat</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pangkat.update', $pangkat->uuid) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name"><strong>Name:</strong></label>
                                <input type="text" name="name" class="form-control" value="{{ $pangkat->name }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                            <a class="btn btn-phoenix-danger me-1 mb-1" href="{{ route('pangkat.index') }}"><i
                                    class="fa fa-arrow-left"></i>
                                Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
@endpush