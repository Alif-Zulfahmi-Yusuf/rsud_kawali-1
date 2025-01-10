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

<div class="card shadow rounded-lg mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
            <h5>Daftar Review Evaluasi Kinerja</h5>
            <form method="GET" action="{{ route('evaluasi-atasan.index') }}"
                class="d-flex flex-wrap align-items-end justify-content-end">
                <!-- Filter Bulan -->
                <div class="me-2 mb-2 flex-grow-1">
                    <select id="bulan" name="bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="me-2 mb-2 flex-grow-1">
                    <select id="tahun" name="tahun" class="form-control">
                        <option value="">Semua Tahun</option>
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Tombol Filter -->
                <div class="mb-2">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table id="tableEvaluasi" class="table table-bordered table-sm fs-9 mb-0">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2" class="text-center align-middle" width="5%">No</th>
                        <th rowspan="2" class="text-center align-middle" width="25%">NIP/Nama</th>
                        <th rowspan="2" class="text-center align-middle" width="10%">Tanggal</th>
                        <th colspan="3" class="text-center align-middle" width="15%">Capaian Rencana Aksi (%)</th>
                        <th colspan="6" class="text-center align-middle" width="25%">Perilaku</th>
                        <th colspan="3" class="text-center align-middle" width="20%">Predikat Kerja</th>
                        <th rowspan="2" class="text-center align-middle" width="10%">Status</th>
                        <th rowspan="2" width="10%"></th>
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
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($EvaluasiAtasan as $data)
                    <tr data-id="{{ $data->uuid }}">
                        <td class="text-center align-middle">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $data->user->name }}<br>
                            {{ $data->user->nip }}

                        </td>
                        <td class="text-center align-middle">
                            {{ $data->tanggal_capaian ? \Carbon\Carbon::parse($data->tanggal_capaian)->format('Y M d') : '-' }}
                        </td>
                        <td></td>
                        <td></td>
                        <td>

                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>

                        </td>
                        <td class="text-center align-middle">
                            @if ($data->status === 'review')
                            <span class="badge badge-phoenix badge-phoenix-warning">Review</span>
                            @elseif ($data->status === 'selesai')
                            <span class="badge badge-phoenix badge-phoenix-success">Selesai</span>
                            @elseif ($data->status === 'revisi')
                            <span class="badge badge-phoenix badge-phoenix-danger">Revisi</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            <a href="{{ route('evaluasi-atasan.edit', ['uuid' => $data->uuid, 'pegawai_id' => $data->user_id]) }}"
                                class="btn btn-link btn-sm">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>

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