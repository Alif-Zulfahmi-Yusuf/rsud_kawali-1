@extends('backend.layouts.app')

@section('title', 'Review Form SKP')

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
<form action="{{ route('validasi.update', $skpDetail->uuid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="30%">Rencana Hasil Kerja Pimpinan yang Diintervensi</th>
                                <th width="30%">Rencana Hasil Kerja</th>
                                <th width="15%">Aspek</th>
                                <th width="35%">Indikator Individu</th>
                                <th width="15%">Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skpDetail->skpAtasan->rencanaHasilKinerja ?? [] as $rencana)
                            @foreach ($rencana->rencanaPegawai ?? [] as $pegawai)
                            @foreach ($pegawai->indikatorKinerja ?? [] as $indikator)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td>{{ $rencana->rencana ?? 'Data Rencana Tidak Tersedia' }}</td>
                                <td>{{ $pegawai->rencana ?? 'Data Rencana Pegawai Tidak Tersedia' }}</td>
                                <td>{{ $indikator->aspek ?? '-' }}</td>
                                <td>{{ $indikator->indikator_kinerja ?? '-' }}</td>
                                <td>{{ $indikator->target_minimum ?? 0 }} - {{ $indikator->target_maksimum ?? 0 }}<br>
                                    {{ $indikator->satuan ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="mb-5">Catatan</h5>
                <div class="form-group mb-3">
                    <textarea name="keterangan_revisi" class="form-control" style="height: 150px;"
                        placeholder="Masukkan keterangan jika diperlukan..."></textarea>
                </div>
                <div>
                    <button type="submit" name="approve" class="btn btn-secondary me-1 mb-1">Approve</button>
                    <button type="submit" name="revisi" class="btn btn-danger me-1 mb-1">Revisi</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/validasi.js') }}"></script>
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