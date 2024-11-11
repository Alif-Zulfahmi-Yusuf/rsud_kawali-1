@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('header')
{{ __('Dashboard') }}
@endsection

@section('content')

<div class="container">
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

    <!-- Row for User Profile and Monthly Performance Chart -->
    <div class="row mb-4">
        <!-- User Profile Card -->
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="card shadow-sm text-center border-0">
                <div class="card-body">
                    <!-- Profile Image -->
                    <img src="{{ Auth::user()->image ? url('storage/' . Auth::user()->image) : url('storage/images/default.png') }}"
                        alt="User Profile" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">

                    <!-- User Name -->
                    <h5 class="card-title mb-2">{{ Auth::user()->name }}</h5>

                    <!-- NIP -->
                    <p class="text-muted mb-3">{{ Auth::user()->nip }}</p>

                    <hr />

                    <!-- User Details -->
                    <p class="small mb-1">
                        <strong>Pangkat:</strong> {{ Auth::user()->pangkat->name ?? 'Unknown Pangkat' }}
                    </p>
                    <p class="small mb-1">
                        <strong>Unit Kerja:</strong> {{ Auth::user()->unit_kerja ?? 'Unknown Unit' }}
                    </p>
                    <p class="small mb-1">
                        <strong>TMT Jabatan:</strong> {{ Auth::user()->tmt_jabatan ?? 'Unknown TMT' }}
                    </p>
                </div>
            </div>
        </div>


        <!-- Monthly Performance Chart Card -->
        <div class="col-12 col-md-9">
            <div class="card shadow rounded">
                <div class="card-header">
                    <h6 class="m-0">Rating Kinerja Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="echart-line-chart-example" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/backend/js/echarts-example.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>

@endpush