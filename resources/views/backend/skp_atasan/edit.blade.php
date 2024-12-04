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
<form action="{{ route('skp_atasan.update', $skpDetail->uuid) }}" method="POST">
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
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalRencana">Rencana Hasil
                                    Kerja Atasan
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
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skpDetail->rencanaHasilKinerja as $index => $rencana)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $rencana->name ?? 'Tidak Ada Nama' }}</td>
                            <td class="text-center">
                                <!-- Contoh Action: Tombol Edit -->
                                <a href="{{ route('rencana.edit', $rencana->uuid) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</form>
@endsection

@include('backend.skp_atasan._modalRencana')

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/skp_atasan.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError({
    message: "{{ session('error') }}", // Mengirim string error
});
@endif
</script>

@endpush