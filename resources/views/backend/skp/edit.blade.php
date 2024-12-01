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
<form action="{{ route('skp.update', $skpDetail->uuid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-plus me-1"></i> Add Action
                        </button>
                        <ul class="dropdown-menu">
                            @can('rencana-atasan-create')
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalRencana">Rencana Hasil
                                    Kerja Atasan
                                </a>
                            </li>
                            @endcan
                            @can('rencana-pegawai-create')
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalRencanaPegawai">Rencana Hasil
                                    Kerja
                                </a>
                            </li>
                            @endcan
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
                        @foreach ($skpDetail->rencanaHasilKinerja as $rencana)
                        <!-- Baris Detail -->
                        @foreach ($rencana->rencanaPegawai ?? [] as $pegawai)
                        <!-- Cek jika rencanaPegawai tidak null -->
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
                            <!-- Tetap untuk kolom Rencana -->
                            <td class="text-center">{{ $indikator->aspek }}</td>
                            <td>{{ $indikator->indikator_kinerja }}</td>
                            <td class="text-center">{{ $indikator->tipe_target }}</td>
                            <td class="text-center">{{ $indikator->report }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach

                        <!-- Cek jika rencanaAtasan tidak null -->
                        @foreach ($rencana->rencanaAtasan ?? [] as $atasan)
                        <!-- Cek jika rencanaAtasan tidak null -->
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div><strong>Rencana Hasil Kerja:</strong></div>
                                <div>{{ 'Data Rencana Pegawai Tidak Ada' }}</div>

                                <div class="text-muted"><strong>Rencana Hasil Kerja Pimpinan yang Diintervensi:</strong>
                                </div>
                                <div class="text-muted">{{ $atasan->rencana }}</div>
                            </td>
                            <!-- Tetap untuk kolom Rencana -->
                            <td class="text-center">{{ $indikator->aspek }}</td>
                            <td>{{ $indikator->indikator_kinerja }}</td>
                            <td class="text-center">{{ $indikator->tipe_target }}</td>
                            <td class="text-center">{{ $indikator->report }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
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
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Ukuran keberhasilan/ Indikator Kinerja dan Target:
                                Memahami dan memenuhi kebutuhan masyarakat
                                Ramah, cekatan, solutif, dan dapat diandalkan
                                Melakukan perbaikan tiada henti</td>
                            <td>
                                <textarea name="" id="" class="form-control"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">Simpan</button>
</form>
@endsection

@include('backend.skp._modalRencana')
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
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}"); // Mengirim string error
@endif
</script>
@endpush