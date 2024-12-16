@extends('backend.layouts.app')

@section('title', 'Kegiatan Harian')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Kegiatan Harian</h2>
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

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data List</h5>
            <a class="btn btn-phoenix-success" href="#" data-bs-toggle="modal" data-bs-target="#addHarianModal">
                <i class="fa fa-plus me-1"></i> Tambah Data
            </a>
        </div>
        <div class="table-responsive p-3">
            <table id="tableKegiatan" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th width="5%" class=" text-center">NO</th>
                        <th class="text-center">NIP/NAMA</th>
                        <th class="text-center">GOL</th>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">URAIAN</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">BIAYA</th>
                        <th class="text-center">WAKTU</th>
                        <th class="text-center">STATUS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data Kosong -->
                    @foreach ($kegiatanHarian as $kegiatan)
                    <tr data-uuid="{{ $kegiatan->uuid }}">
                        <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                        <td class="text-center">
                            {{ $kegiatan->user->name }}
                            <br>
                            {{ $kegiatan->user->nip }}
                        </td>
                        <td class="text-center">{{ $kegiatan->user->pangkat->name }}</td>
                        <td class="text-center">
                            {{ $kegiatan->tanggal ? \Carbon\Carbon::parse($kegiatan->tanggal)->format('Y M d') : '-' }}
                        </td>
                        <td>{{ $kegiatan->uraian }}</td>
                        <td class="text-center">{{ $kegiatan->jumlah }}</td>
                        <td class="text-center">
                            Rp {{ number_format((float) ($kegiatan->biaya ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($kegiatan->waktu_mulai)->format('H:i') }} sd
                            {{ \Carbon\Carbon::parse($kegiatan->waktu_selesai)->format('H:i') }}
                        </td>
                        <td class="text-center">
                            @if ($kegiatan->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                            @elseif ($kegiatan->status == 'approve')
                            <span class="badge bg-success">Approve</span>
                            @elseif ($kegiatan->status == 'revisi')
                            <span class="badge bg-danger">Revisi</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-reveal-trigger position-static">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <svg class="fs-10" aria-hidden="true" focusable="false"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
                                        <path fill="currentColor"
                                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a class="dropdown-item {{ $kegiatan->status === 'approve' ? 'disabled' : '' }}"
                                        href="{{ $kegiatan->status === 'approve' ? '#' : route('harian-pegawai.edit', $kegiatan->uuid) }}"
                                        {{ $kegiatan->status === 'approve' ? 'aria-disabled=true' : '' }}>
                                        Edit
                                    </a>
                                    <hr class="dropdown-divider">
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-uuid="{{ $kegiatan->uuid }}">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@include('backend.harian._modal')


@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/kegiatan_harian.js') }}"></script>


<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}");
@endif
</script>
@endpush