@extends('backend.layouts.app')

@section('title', 'Pangkat')

@section('header')
{{ __('Pangkat') }}
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush


@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Form Skp</h2>
    </div>
    <div class="col-md-3 col-auto">
        <div class="input-group flatpickr-input-container">
            <input class="form-control datetimepicker" id="datepicker" type="text"
                data-options='{"dateFormat":"M j, Y","disableMobile":true,"defaultDate":"{{ date('M j, Y') }}"}'
                placeholder="Select Date" />
            <span class="input-group-text"><i class="uil uil-calendar-alt"></i></span>
        </div>
    </div>
</div>

<div class="card shadow border rounded-lg mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                <a class="btn btn-success" href="{{ route('skp.create') }}">
                    <i class="fa fa-plus me-1"></i> Add Atasan
                </a>
            </div>
            <table id="tableSkp" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="sort border-top text-center">No</th>
                        <th class="sort border-top">Jabatan</th>
                        <th class="sort border-top text-center">Unit Keja</th>
                        <th class="sort border-top text-center">Tanggal Skp</th>
                        <th class="sort border-top text-center">Tanggal Akhir</th>
                        <th class="sort border-top">Posisi</th>
                        <th class="sort border-top">Status</th>
                        <th class="sort border-top">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/skp.js') }}"></script>
@endpush