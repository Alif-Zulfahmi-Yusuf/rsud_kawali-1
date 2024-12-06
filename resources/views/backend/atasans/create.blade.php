@extends('backend.layouts.app')

@section('title', 'Create')


@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create Atasan</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-6">
            <div class="card-body">
                <form method="POST" action="{{ route('atasans.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name"><strong>Name:</strong></label>
                                <input type="text" name="name" placeholder="Name" class="form-control"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user_id"><strong>Pilih User:</strong></label>
                                <select class="select-single" name="user_id" required>
                                    <option value="" disabled selected>Pilih User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nip"><strong>NIP:</strong></label>
                                <input type="text" name="nip" placeholder="NIP" class="form-control" required
                                    value="{{ old('nip') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pangkat_id"><strong>Pangkat:</strong></label>
                                <select class="select-single" name="pangkat_id" required>
                                    <option value="" disabled selected>Pilih Pangkat</option>
                                    @foreach ($pangkats as $pangkat)
                                    <option value="{{ $pangkat->id }}">{{ $pangkat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="jabatan"><strong>Jabatan:</strong></label>
                                <input type="text" name="jabatan" placeholder="Jabatan" value="{{ old('jabatan') }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="unitkerja"><strong>Unit Kerja:</strong></label>
                                <input type="text" name="unit_kerja" placeholder="Unit Kerja"
                                    value="{{ old('unit_kerja') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                            <a class="btn btn-primary btn-sm mb-2" href="{{ route('atasans.index') }}"><i
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
<script src="{{ asset('assets/backend/library/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
@if(session('status'))
// Pastikan session('status') adalah string, bukan array
let statusMessage = @json(session('status'));
if (typeof statusMessage === 'object') {
    statusMessage = statusMessage.message || 'Unknown status';
}
toastSuccess(statusMessage);
@endif
</script>
@endpush