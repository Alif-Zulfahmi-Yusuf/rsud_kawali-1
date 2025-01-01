@extends('backend.layouts.app')

@section('title', 'Evaluasi Kinerja')

@push('css')
<!-- data table -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

<!-- select 2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    <form action="">
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <h5>Realisasi Rencana Aksi</h5>
                </div>
                <div class="table-responsive scrollbar">
                    <table class="table small">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">No</th>
                                <th rowspan="2" class="text-center align-middle">Rencana Aksi</th>
                                <th rowspan="2" class="text-center align-middle">Target</th>
                                <th colspan="3" class="text-center">Realisasi</th>
                                <th rowspan="2" class="text-center align-middle">File</th>
                            </tr>
                            <tr>
                                <th class="text-center">Kuantitas Output</th>
                                <th width="10%" class="text-center">Kualitas</th>
                                <th class="text-center">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_rencana_pegawai ?? '-' }}</td>
                                <td>{{ $item->nama_indikator ?? '-' }}</td>
                                <td></td>
                                <td>
                                    <select name="evaluasi[{{ $item->evaluasi_id }}]" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="sangat_kuat">Sangat Kuat</option>
                                        <option value="kurang">Kurang</option>
                                        <option value="butuh_perbaikan">Butuh Perbaikan</option>
                                        <option value="baik">Baik</option>
                                        <option value="sangat_baik">Sangat Baik</option>
                                    </select>
                                </td>
                                <td class="text-center align-middle">
                                    {{ isset($item->waktu_mulai, $item->waktu_selesai) 
                    ? \Carbon\Carbon::parse($item->waktu_mulai)->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai)) . ' Jam' 
                    : '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-file-upload"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <h5>Evaluasi Kinerja Tahunan</h5>
                </div>
                <div class="table-responsive scrollbar">
                    <table class="table small">
                        <thead class="table-dark">
                            <tr>
                                <th>NO</th>
                                <th>
                                    Rencana Hasil Kerja Pimpinan yang Di intervensi
                                </th>
                                <th>Rencana Hasil Kerja</th>
                                <th>Aspek</th>
                                <th>Indikator Kinerja Individu</th>
                                <th>Target</th>
                                <th>Realisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_rencana_pimpinan ?? '-' }}</td>
                                <td>{{ $item->nama_rencana_pegawai ?? '-' }}</td>
                                <td>{{ $item->nama_indikator ?? '-' }}</td>
                                <td>{{ $item->target ?? '-' }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <h5>Input Data Pegawai Fungsional</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Jenjang Jabatan</label>
                            <input type="text" readonly class="form-control" value="{{ Auth::user()->pangkat->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="">Jumlah Periode Penilai Bulanan</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Permasalahan Jika Ada</label>
                            <textarea name="" class="form-control" id="" style="height: 150px;"></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('evaluasi-pegawai.index') }}" class="btn btn-outline-danger me-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-outline-primary">Simpan & Ajukan Preview</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection


@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/evaluasi-pegawai.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

@endpush