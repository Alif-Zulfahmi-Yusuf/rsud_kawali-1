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

<form action="">
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <h5>Realisasi Rencana Aksi</h5>
            <div class="table-responsive scrollbar">
                <table class="table small">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="text-center align-middle" width="5%">No</th>
                            <th rowspan="2" class="text-center align-middle" width="30%">Rencana Aksi</th>
                            <th rowspan="2" colspan="2" class="text-center align-middle" width="5%">Target</th>
                            <th colspan="5" class="text-center align-middle">Realisasi</th>
                            <th rowspan="2" class="text-center align-middle">File</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center align-middle">Kuantitas Output</th>
                            <th class="text-center align-middle">Kualitas</th>
                            <th class="text-center align-middle">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <h5>Evaluasi Kinerja Tahunan</h5>
            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-sm fs-9 mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center align-middle" width="5%">No</th>
                            <th width="10%">Rencana Hasil Kerja Pimpinan</th>
                            <th width="10%">Rencana Hasil Kerja</th>
                            <th class="text-center align-middle" width="7%">Aspek</th>
                            <th class="text-center align-middle" width="20%">Indikator Kinerja Individu</th>
                            <th class="text-center align-middle" width="10%">Target</th>
                            <th class="text-center align-middle" width="20%">Realisasi</th>
                            <th class="text-center align-middle" width="30%">Umpan Balik</th>
                        </tr>
                    </thead>
                    @php
                    $i = 0;
                    @endphp
                    <tbody>
                        @foreach (\App\Models\RencanaHasilKinerja::where('skp_atasan_id',
                        $skpDetail->skp_atasan_id)->get() ?? [] as $rencana)
                        @php
                        $pegawaiList = \App\Models\RencanaHasilKinerjaPegawai::where('skp_id', $skpDetail->id)
                        ->where('rencana_atasan_id', $rencana->id)
                        ->get();
                        @endphp

                        @foreach ($pegawaiList as $pegawai)
                        @php
                        $indikatorList = \App\Models\IndikatorKinerja::where('rencana_kerja_pegawai_id',
                        $pegawai->id)->get();
                        $rowspanIndikator = $indikatorList->count() ?: 1;

                        // Ambil data evaluasi pegawai yang terkait
                        $evaluasiPegawai = \App\Models\EvaluasiPegawai::where('skp_id', $skpDetail->id)
                        ->where('rencana_pegawai_id', $pegawai->id)
                        ->first();
                        @endphp

                        @foreach ($indikatorList as $indikator)
                        <tr>
                            @if ($loop->parent->first && $loop->first)
                            <td class="align-middle text-center"
                                rowspan="{{ $pegawaiList->count() * $rowspanIndikator }}">
                                {{ $loop->iteration }}
                            </td>
                            <td class="align-middle" rowspan="{{ $pegawaiList->count() * $rowspanIndikator }}">
                                {{ $rencana->rencana ?? 'Data Tidak Tersedia' }}
                            </td>
                            @endif
                            @if ($loop->first)
                            <td class="align-middle" rowspan="{{ $rowspanIndikator }}">
                                {{ $pegawai->rencana ?? 'Data Tidak Tersedia' }}
                            </td>
                            @endif

                            <td class="align-middle text-center">{{ $indikator->aspek ?? '-' }}</td>
                            <td>{{ $indikator->indikator_kinerja ?? '-' }}</td>
                            <td class="align-middle text-center">
                                {{ $indikator->target_minimum ?? 0 }} - {{ $indikator->target_maksimum ?? 0 }}<br>
                                {{ $indikator->satuan ?? '-' }}
                            </td>
                            <td class="align-middle text-center">
                                <input type="text" class="form-control" name="realisasi[]"
                                    value="{{ $evaluasi->realisasi[$i++] ?? '' }}">
                            </td>
                            <td>
                                <textarea name="umpan_balik[]" id="" class="form-control">
                                {{ $evaluasiPegawai->umpan_balik ?? '' }}
                                </textarea>
                            </td>
                        </tr>
                        @endforeach
                        @if ($indikatorList->isEmpty())
                        <tr>
                            @if ($loop->first)
                            <td class="align-middle" rowspan="1">
                                {{ $pegawai->rencana ?? 'Data Tidak Tersedia' }}
                            </td>
                            @endif
                            <td class="align-middle text-center">-</td>
                            <td>-</td>
                            <td class="align-middle text-center">-</td>
                        </tr>
                        @endif
                        @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="align-middle">
                                Rating Hasil kinerja :
                            </td>
                            <td colspan="2">
                                <select style="width: 40%;" name="rating" id="" class="form-select form-select-sm">
                                    <option value="">Pilih</option>
                                    <option value="dibawah_expektasi">Di Bawah Expektasi</option>
                                    <option value="sesuai_expektasi">Sesuai Expektasi</option>
                                    <option value="diatas_expektasi">Di Atas Expektasi</option>
                                </select>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <h5 class="mb-5">Perilaku Kerja</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm fs-9 mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" colspan="3">PERILAKU KERJA / BEHAVIOUR</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Umpan Balik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr class="bg-light">
                            <td colspan="5" class="fw-bold text-start">{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="5%">{{ $loop->iteration }}</td>
                            <td class="align-middle" width="30%">
                                <h6 class="fw-bold mb-2">Ukuran Keberhasilan/Indikator Kinerja dan Target:</h6>
                                <ul class="mb-0">
                                    @foreach ($category->perilakus as $perilaku)
                                    <li>{{ $perilaku->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="align-middle" width="30%">
                                <h6 class="fw-bold mb-2">Ekspektasi Khusus Pimpinan/Leader:</h6>
                                @php
                                // Cari ekspektasi berdasarkan category_id
                                $ekspetasi = $ekspektasis->firstWhere('category_id', $category->id);
                                @endphp
                                <li>
                                    {{ $ekspetasi->ekspetasi ?? 'Ekspektasi belum diinputkan.' }}
                                </li>
                            </td>
                            <td class="align-middle">
                                <select name="nilai" id="" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="dibawah_expektasi">Di Bawah Expektasi</option>
                                    <option value="sesuai_expektasi">Sesuai Expektasi</option>
                                    <option value="diatas_expektasi">Di Atas Expektasi</option>

                                </select>
                            </td>
                            <td class="align-middle">
                                <textarea name="umpan_balik_berkelanjutan" id="" class="form-control">

                                </textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                Rating Perilaku Kerja :
                            </td>
                            <td>
                                <input type="text" class="form-control" readonly>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                <h5>Hasil Penilaian</h5>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">

                    </div>
                    <div class="mb-3">
                        <h5>Input Data Pegawai Fungsional</h5>
                    </div>
                    <div class="mb-3">
                        <label for="">
                            <small>Jenjang Jabatan</small>
                        </label>
                        <input style="width: 40%;" type="text" readonly class="form-control"
                            value="{{ Auth::user()->pangkat->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="">
                            <small>Jumlah Periode Penilai Bulanan</small>
                        </label>
                        <input style="width: 40%;" type="text" readonly class="form-control" value="">
                    </div>
                    <div class="mb-3">
                        <h5>Form Review</h5>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                            <label for="">
                                <small>Rating Hasil Kinerja</small>
                            </label>
                            <select style="width: 30%;" name="status" id="rating" class="form-select form-select-sm">
                                <option value="">Pilih</option>
                                <option value="approve">Approve</option>
                                <option value="revisi">Revisi</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                            <label for="">
                                <small>Tanggal Terbit</small>
                            </label>
                            <input style="width: 50%;" type="date" name="tanggal_terbit" id="" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                            <label for="">
                                <small>Keterangan</small>
                            </label>
                            <textarea name="keterangan" style="width: 70%;" id="" cols="10" rows="5"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('evaluasi-atasan.index') }}" class="btn btn-outline-danger me-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-outline-secondary" name="action" value="submit">
                            <i class="fas fa-save"></i> Save Review
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('js')

@endpush