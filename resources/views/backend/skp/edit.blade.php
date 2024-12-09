@extends('backend.layouts.app')

@section('title', 'Data Skp')

@section('header')
{{ __('Data Skp') }}
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

@endpush


@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Data SKP (Kinerja Utama)</h2>
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

<div class="row gy-4 mb-5">
    <!-- Card Pegawai yang Dinilai -->
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header text-white text-center rounded-top">
                <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Pegawai yang Dinilai</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td><strong><i class="fas fa-user me-2 text-primary"></i>Nama</strong></td>
                            <td>: {{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-id-badge me-2 text-primary"></i>NIP</strong></td>
                            <td>: {{ Auth::user()->nip }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-building me-2 text-primary"></i>Unit Kerja</strong></td>
                            <td>: {{ Auth::user()->unit_kerja }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-layer-group me-2 text-primary"></i>Pangkat</strong></td>
                            <td>: {{ Auth::user()->pangkat->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card Atasan Penilai -->
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header  text-white text-center rounded-top">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Atasan Penilai</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td><strong><i class="fas fa-user me-2 text-success"></i>Nama</strong></td>
                            <td>: {{ Auth::user()->atasan->name }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-id-badge me-2 text-success"></i>NIP</strong></td>
                            <td>: {{ Auth::user()->atasan->nip }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-building me-2 text-success"></i>Unit Kerja</strong></td>
                            <td>: {{ Auth::user()->atasan->unit_kerja }}</td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-layer-group me-2 text-success"></i>Pangkat</strong></td>
                            <td>: {{ Auth::user()->atasan->pangkat->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('skp.update', $skpDetail->uuid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-plus me-1"></i> Add Action
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalRencanaPegawai">Rencana Hasil Kerja
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalIndikator">
                                    Indikator Individu
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <table id="tableRencana" class="table table-hover table-sm fs-9 mb-0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-center">Rencana Hasil Kerja</th>
                            <th class="text-center">Aspek</th>
                            <th class="text-center">Indikator Kinerja</th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Report</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skpDetail->skpAtasan->rencanaHasilKinerja as $rencana)
                        @if ($rencana->rencanaPegawai->isNotEmpty())
                        @foreach ($rencana->rencanaPegawai as $pegawai)
                        @foreach ($pegawai->indikatorKinerja as $indikator)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div><strong>Rencana Hasil Kerja:</strong></div>
                                <div>{{ $pegawai->rencana ?? 'Data Rencana Pegawai Tidak Tersedia' }}</div>
                                <div class="text-muted"><strong>Rencana Hasil Kerja Pimpinan yang Diintervensi:</strong>
                                </div>
                                <div class="text-muted">{{ $rencana->rencana }}</div>
                            </td>
                            <td class="text-center">{{ $indikator->aspek }}</td>
                            <td>{{ $indikator->indikator_kinerja }}</td>
                            <td class="text-center">
                                {{ $indikator->target_minimum }} - {{ $indikator->target_maksimum }}<br>
                                {{ $indikator->satuan }}
                            </td>
                            <td class="text-center">{{ $indikator->report }}</td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                    type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-10"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalEditIndikator"
                                        onclick="openEditIndikatorModal('{{ $indikator->uuid }}', '{{ $indikator->rencana_kerja_pegawai_id }}', '{{ $indikator->aspek }}', '{{ $indikator->indikator_kinerja }}', '{{ $indikator->tipe_target }}', '{{ $indikator->target_minimum }}', '{{ $indikator->target_maksimum }}', '{{ $indikator->satuan }}', '{{ $indikator->report }}')">
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-uuid="{{ $indikator->uuid }}">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data rencana.</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="mb-5">Perilaku Kerja (BerAKHLAK)</h5>
            <div class="table-responsive">
                <table class="table table-hover table-sm fs-9 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="3">PERILAKU KERJA / BEHAVIOUR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td colspan="3" class="fw-bold bg-light">{{ $category->name }}</td>
                        </tr>
                        @foreach ($category->perilakus as $perilaku)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="">
                                <h6>Ukuran keberhasilan/ Indikator Kinerja dan Target:</h6>
                                {{ $perilaku->name }}
                            </td>
                            <td>
                                <h6>Ekspektasi Khusus Pimpinan/ Leader:</h6>
                                <textarea class="form-control" name="" id=""></textarea>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>
    <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">Simpan</button>
</form>
@endsection

@include('backend.skp._modalEditIndikator')
@include('backend.skp._modalRencanaPegawai')
@include('backend.skp._modalIndikator')

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/skp.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
$(document).ready(function() {
    @if(session('success'))
    toastSuccess("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastError("{{ session('error') }}");
    @endif
});
</script>

@endpush