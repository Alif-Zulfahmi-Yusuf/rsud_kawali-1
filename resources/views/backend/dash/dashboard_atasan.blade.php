@extends('backend.layouts.app')

@section('title', 'Dashboard Atasan')

@section('header')
{{ __('Dashboard Atasan') }}
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
<div class="row g-4 mb-4">

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm text-center border-0">
            <div class="card-body">
                <!-- Profile Image -->
                <img src="{{ Auth::user()->image ? url('storage/' . Auth::user()->image) : url('storage/images/default.png') }}"
                    alt="User Profile" class="img-fluid rounded-circle mb-3 profile-img">

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

    <div class="col-12 col-lg-9">
        <div class="card shadow-sm rounded">
            <div class="card-header">
                <form action="{{route('getEvaluasiPegawai')}}" method="POST">
                    @csrf
                    <div class="input-group">
                        <select id="select-pegawai" name="pegawai" class="form-select">
                            <option value="">--Pilih--</option>
                            @foreach($pegawaiList as $pegawai)
                            <option value="{{ $pegawai->user_id }}"
                                {{ old('pegawai', session('pegawai')) == $pegawai->user_id ? 'selected' : '' }}>
                                {{ $pegawai->user->name }} -
                                {{ $pegawai->user->nip }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                {{ $chart->container() }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}

<script>
document.addEventListener("DOMContentLoaded", function() {
    const categories = ['Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];

    document.addEventListener('apexcharts:rendered', function() {
        ApexCharts.exec('{{ $chart->id }}', 'updateOptions', {
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return categories[value - 1] || 'Tidak Ada Data';
                    }
                }
            }
        });
    });
});
</script>
@endpush