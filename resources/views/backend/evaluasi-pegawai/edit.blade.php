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
    <form action="{{ route('evaluasi-pegawai.update', $evaluasi->uuid) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $evaluasi->id }}">
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <h5>Realisasi Rencana Aksi</h5>
                    <a href="{{ route('evaluasi-pegawai.pdf', $evaluasi->uuid) }}" class="btn btn-danger"
                        target="_blank">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
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
                                <th class="text-center align-middle" width="15%">Kualitas</th>
                                <th class="text-center align-middle">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($dataRencanaAksi->isEmpty())
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data untuk bulan dan tahun ini.</td>
                            </tr>
                            @else
                            @php

                            @endphp
                            @foreach ($dataRencanaAksi as $item)
                            @php
                            #idEvaluasi = [];
                            $idEvaluasi[] = $item->evaluasi_pegawai_id;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_rencana_pegawai ?? '-' }}</td>
                                <td class="text-center align-middle" width="5%">
                                    {{ $item->bulan_muncul ?? '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ $item->satuan }}
                                </td>
                                <td class="text-center align-middle" width="5%">
                                    <input type="text" name="kuantitas_output[{{ $item->rencana_pegawai_id }}]"
                                        class="form-control"
                                        value="{{ $evaluasi->kuantitas_output[$loop->index] ?? '' }}">
                                </td>
                                <td class="text-center align-middle">
                                    {{ $item->satuan }}
                                </td>
                                <td>
                                    <input type="radio" id="ada" name="laporan[{{ $item->rencana_pegawai_id }}]"
                                        value="ada"
                                        {{ isset($evaluasi->laporan[$loop->index]) && $evaluasi->laporan[$loop->index] == 'ada' ? 'checked' : '' }}>
                                    <label for="ada">Ada</label><br>

                                    <input type="radio" id="tidak_ada" name="laporan[{{ $item->rencana_pegawai_id }}]"
                                        value="tidak_ada"
                                        {{ isset($evaluasi->laporan[$loop->index]) && $evaluasi->laporan[$loop->index] == 'tidak_ada' ? 'checked' : '' }}>
                                    <label for="tidak_ada">Tidak Ada</label><br>
                                </td>
                                <td>
                                    <select name="kualitas[{{ $item->rencana_pegawai_id }}]" class="form-select">
                                        <option value="{{ $evaluasi->kualitas[$loop->index] ?? '' }}">
                                            {{ isset($evaluasi->kualitas[$loop->index]) ? ucwords(str_replace('_', ' ', $evaluasi->kualitas[$loop->index])) : 'Pilih' }}
                                        </option>
                                        <option value="sangat_kurang"
                                            {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'sangat_kurang' ? 'selected' : '' }}>
                                            Sangat Kurang</option>
                                        <option value="kurang"
                                            {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'kurang' ? 'selected' : '' }}>
                                            Kurang</option>
                                        <option value="butuh_perbaikan"
                                            {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'butuh_perbaikan' ? 'selected' : '' }}>
                                            Butuh Perbaikan</option>
                                        <option value="baik"
                                            {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'baik' ? 'selected' : '' }}>
                                            Baik</option>
                                        <option value="sangat_baik"
                                            {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'sangat_baik' ? 'selected' : '' }}>
                                            Sangat Baik</option>
                                    </select>
                                </td>
                                <td class="text-center align-middle">
                                    {{ $item->waktu_total ? $item->waktu_total . ' Menit' : '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    @if ($item->file_realisasi)
                                    <a href="{{ asset('storage/' . $item->file_realisasi) }}" target="_blank"
                                        class="btn btn-outline-secondary btn-sm"><i class="fa fa-eye"></i></a></a>
                                    @endif
                                    <a href="#" class="btn btn-outline-warning btn-sm upload-btn"
                                        data-evaluasi-id="{{ $idEvaluasi[0] }}"
                                        data-rencana-id="{{ $item->rencana_pegawai_id }}">
                                        <i class="fa fa-upload"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
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
                                <th class="text-center" width="5%">No</th>
                                <th width="10%">Rencana Hasil Kerja Pimpinan</th>
                                <th width="10%">Rencana Hasil Kerja</th>
                                <th class="text-center align-middle" width="7%">Aspek</th>
                                <th class="text-center align-middle" width="20%">Indikator Kinerja Individu</th>
                                <th class="text-center align-middle" width="10%">Target</th>
                                <th class="text-center align-middle" width="30%">Realisasi</th>
                            </tr>
                        </thead>
                        @php
                        $i = 0;
                        @endphp
                        <tbody>
                            @foreach ($groupedDataEvaluasi as $rencanaPimpinan => $pegawaiItems)
                            @foreach ($pegawaiItems as $rencanaPegawai => $items)
                            @php
                            $rowspan = count($items); // Hitung jumlah item dalam grup
                            @endphp
                            @foreach ($items as $index => $item)
                            <tr>
                                @if ($index == 0) {{-- Hanya render rowspan pada baris pertama --}}
                                <td class="align-middle text-center" rowspan="{{ $rowspan }}">
                                    {{ $loop->parent->parent->iteration }}
                                </td>
                                <td class="align-middle" rowspan="{{ $rowspan }}">{{ $rencanaPimpinan }}</td>
                                <td class="align-middle" rowspan="{{ $rowspan }}">{{ $item->rencana_pegawai ?? '-' }}
                                </td>
                                @endif
                                <td class="align-middle text-center">{{ $item->aspek ?? '-' }}</td>
                                <td class="align-middle">{{ $item->nama_indikator ?? '-' }}</td>
                                <td class="align-middle text-center">
                                    {{ $item->target_minimum ?? 0 }} - {{ $item->target_maksimum ?? 0 }}<br>
                                    {{ $item->satuan ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    <input type="text" name="realisasi[]" class="form-control"
                                        value="{{ $evaluasi->realisasi[$i++] ?? '' }}">
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
                            <input type="text" name="jumlah_periode" class="form-control"
                                value="{{ $evaluasi->jumlah_periode ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="">Tanggal Capai</label>
                            <input type="date" name="tanggal_capai" id="" class="form-control"
                                value="{{ \Carbon\Carbon::parse($evaluasi->tanggal_capaian)->format('Y-m-d') ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="">Permasalahan Jika Ada</label>
                            <textarea name="permasalahan" class="form-control" id=""
                                style="height: 150px;">{{ $evaluasi->permasalahan ?? '' }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('evaluasi-pegawai.index') }}" class="btn btn-outline-danger me-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-outline-secondary" name="action" value="submit">
                                Simpan & Ajukan Preview
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection


<!-- Modal Upload File -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadFileForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Upload File Realisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="evaluasi_pegawai_id" id="evaluasi_pegawai_id">
                    <input type="hidden" name="rencana_pegawai_id" id="rencana_pegawai_id">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>



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

document.addEventListener('DOMContentLoaded', function() {
    // Buka modal dengan data
    document.querySelectorAll('.upload-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const evaluasiId = this.getAttribute('data-evaluasi-id');
            const rencanaId = this.getAttribute('data-rencana-id');

            // Set data ke modal
            document.getElementById('evaluasi_pegawai_id').value = evaluasiId;
            document.getElementById('rencana_pegawai_id').value = rencanaId;

            // Tampilkan modal
            const uploadFileModal = new bootstrap.Modal(document.getElementById(
                'uploadFileModal'));
            uploadFileModal.show();
        });
    });

    // Proses upload file
    document.getElementById('uploadFileForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("realisasi.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'File berhasil diupload!',
                }).then(() => {
                    location.reload();
                });
            } else {
                const error = await response.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.',
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tidak dapat menghubungi server.',
            });
        }
    });
});
</script>

@endpush