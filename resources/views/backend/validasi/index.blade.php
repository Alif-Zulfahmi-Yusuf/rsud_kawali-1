@extends('backend.layouts.app')


@section('title', 'Validasi')

@push('css')

@endpush

@section('content')

<div class="row gy-3 mb-4 justify-content-between align-items-center">
    <div class="col-md-9 col-auto">
        <h2 class="text-body-emphasis">Review Form SKP</h2>
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

@endsection


@push('js')


@endpush