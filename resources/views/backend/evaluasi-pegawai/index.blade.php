@extends('backend.layouts.app')


@section('title', 'Evaluasi Kinerja')

@push('css')

@endpush

@section('content')

<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Evaluasi Kinerja</h2>
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

<div class="container">
    <div class="row gy-4 mb-5">
        <!-- Card Pegawai yang Dinilai -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-body">
                    <table class="table small">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" colspan="2">
                                    <i class="fas fa-user-check me-2"></i>
                                    Pegawai yang Dinilai
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ Auth::user()->nip }}</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>{{ Auth::user()->unit_kerja }}</td>
                            </tr>
                            <tr>
                                <td>Pangkat</td>
                                <td>{{ Auth::user()->pangkat->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card Atasan Penilai -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-body">
                    <table class="table small">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" colspan="2">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Atasan Penilai
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>{{ Auth::user()->atasan->name }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>{{ Auth::user()->atasan->nip }}</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>{{ Auth::user()->atasan->unit_kerja }}</td>
                            </tr>
                            <tr>
                                <td>Pangkat</td>
                                <td>{{ Auth::user()->atasan->pangkat->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                <h5>Data Evaluasi Kinerja</h5>
                <a class="btn btn-phoenix-success me-1 mb-1" href="#" data-bs-toggle="modal"
                    data-bs-target="#addEvaluasiModal">
                    <i class="fa fa-plus me-1"></i> Tambah Data
                </a>
            </div>
            <div class="table-responsive">
                <table id="tableEvaluasi" class="table table-bordered table-sm fs-9 mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="text-center align-middle" width="5%">No</th>
                            <th rowspan="2" class="text-center align-middle" width="10%">Bulan</th>
                            <th rowspan="2" class="text-center align-middle">Tanggal</th>
                            <th colspan="3" class="text-center align-middle" width="10%">Capaian Rencana Aksi (%)</th>
                            <th colspan="6" class="text-center align-middle">Perilaku</th>
                            <th colspan="2" class="text-center align-middle">Predikat Kerja</th>
                            <th rowspan="2" class="text-center align-middle">Posisi</th>
                            <th rowspan="2" class="text-center align-middle">Status</th>
                            <th rowspan="2" width="5%"></th>
                        </tr>
                        <tr>
                            <th class="text-center">QTY</th>
                            <th class="text-center">QLY</th>
                            <th class="text-center">WKT</th>
                            <th class="text-center">BER</th>
                            <th class="text-center">A</th>
                            <th class="text-center">K</th>
                            <th class="text-center">H</th>
                            <th class="text-center">L</th>
                            <th class="text-center">AK</th>
                            <th class="text-center">Hasil Kerja</th>
                            <th class="text-center">Perilaku</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Tambahkan data di sini -->
                        @foreach($evaluasiPegawai as $evaluasi)
                        <tr data-uuid="{{ $evaluasi->uuid }}">
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($evaluasi->bulan)->translatedFormat('F') }}
                            </td>
                            <td class="text-center align-middle">
                                @if($evaluasi->tanggal_capaian)
                                {{ $evaluasi->tanggal_capaian->format('d-m-Y') }}
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center align-middle">{{ $evaluasi->capaian_qty }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->capaian_qlty }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->capaian_wkt }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->ber }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->a }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->k }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->h }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->l }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->ak }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->hasil_kerja }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->perilaku }}</td>
                            <td class="text-center align-middle">{{ $evaluasi->posisi }}</td>
                            <td class="text-center align-middle">
                                @if ($evaluasi->status === 'review')
                                <span class="badge badge-phoenix badge-phoenix-warning">Review</span>
                                @elseif ($evaluasi->status === 'selesai')
                                <span class="badge badge-phoenix badge-phoenix-success">Selesai</span>
                                @elseif ($evaluasi->status === 'revisi')
                                <span class="badge badge-phoenix badge-phoenix-danger">Revisi</span>
                                @else
                                <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-reveal-trigger position-static">
                                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <svg class="fs-10" aria-hidden="true" focusable="false"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16"
                                            height="16">
                                            <path fill="currentColor"
                                                d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                            </path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a class="dropdown-item"
                                            href="{{ route('evaluasi-pegawai.edit', $evaluasi->uuid) }}">
                                            Edit
                                        </a>
                                        <hr class="dropdown-divider">
                                        <button type="button" class="dropdown-item text-danger delete-button"
                                            onclick="deleteData(this)" data-uuid="{{ $evaluasi->uuid }}">Delete</button>
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

</div>

@endsection

@include('backend.evaluasi-pegawai._modal')

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/evaluasi-pegawai.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}");
@endif
</script>
@endpush