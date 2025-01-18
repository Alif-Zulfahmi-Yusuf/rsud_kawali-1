@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create New User</h2>
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
                <h4 class="card-title">Create New User</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate="">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name"><strong>Name:</strong></label>
                                <input type="text" name="name" placeholder="Name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email"><strong>Email:</strong></label>
                                <input type="email" name="email" placeholder="Email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-mb-12">
                            <div class="form-group">
                                <label for="nip"><strong>NIP:</strong></label>
                                <input type="text" name="nip" placeholder="NIP" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-mb-12">
                            <div class="form-group">
                                <label for="unitkerja"><strong>Unit Kerja:</strong></label>
                                <input type="text" name="unit_kerja" placeholder="unitkerja" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password"><strong>Password:</strong></label>
                                <input type="password" name="password" placeholder="Password" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="confirm-password"><strong>Confirm Password:</strong></label>
                                <input type="password" name="confirm-password" placeholder="Confirm Password"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="roles"><strong>Role:</strong></label>
                                <select name="roles[]" class="form-control" multiple="multiple" id="roles">
                                    @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                            <a class="btn btn-phoenix-danger me-1 mb-1" href="{{ route('users.index') }}"><i
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