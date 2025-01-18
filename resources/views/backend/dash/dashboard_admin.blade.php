@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('header')
{{ __('Dashboard') }}
@endsection

@push('css')
<style>
.profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.card-body hr {
    margin: 1rem 0;
}

@media (max-width: 800px) {
    .echart-basic-bar-chart-example {
        min-height: 250px;
    }
}
</style>
@endpush

@section('content')

<!-- Header Row with Title and Date Picker -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
    <div class="col-md-9 col-auto">
        <h2 class="text-body-emphasis">Dashboard E-Kinerja</h2>
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

<div class="row gy-4">
    <!-- Card untuk Total Atasan -->
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Atasan</h5>
                <h2 class="text-primary">
                    <span class="fs-4 lh-1 uil uil-users-alt text-primary-dark"></span>
                    {{ $totalAtasan }}
                </h2>
                <p class="card-text">Jumlah total pengguna dengan peran Atasan.</p>
            </div>
        </div>
    </div>

    <!-- Card untuk Total Pegawai -->
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-success">Total Pegawai</h5>
                <h2 class="text-success">
                    <span class="fs-4 lh-1 uil uil-users-alt text-success-dark"></span>
                    {{ $totalPegawai }}
                </h2>
                <p class="card-text">Jumlah total pengguna dengan peran Pegawai.</p>
            </div>
        </div>
    </div>
</div>
@endsection