@extends('backend.layouts.app')

@section('title', 'Perilaku Kerja')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Catatan Perilaku Kerja</h2>
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
                <a class="btn btn-outline-success" href="{{ route('perilaku.create') }}">
                    <i class="fa fa-plus me-1"></i> Add Perilaku
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush