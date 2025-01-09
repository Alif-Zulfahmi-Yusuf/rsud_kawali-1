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

<form action="{{ route('evaluasi-atasan.update', $evaluasi->uuid) }}" method="POST">
    @csrf
    @method('PUT')
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
                        @foreach ($dataRencanaAksi as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_rencana_pegawai ?? '-' }}</td>
                            <td class="text-center align-middle" width="5%">
                                {{ $item->target_bulanan ?? '-' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $item->satuan}}
                            </td>
                            <td class="text-center align-middle" width="5%">
                                <input type="text" name="kuantitas_output[{{ $item->rencana_pegawai_id }}]"
                                    class="form-control" value="{{ $evaluasi->kuantitas_output[$loop->index] ?? '' }}">
                            </td>
                            <td class="text-center align-middle">
                                {{ $item->satuan}}
                            </td>
                            <td>
                                <input type="radio" id="ada" name="laporan[{{ $item->rencana_pegawai_id }}]" value="ada"
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
                                    <option value="sangat_kuat"
                                        {{ isset($evaluasi->kualitas[$loop->index]) && $evaluasi->kualitas[$loop->index] == 'sangat_kuat' ? 'selected' : '' }}>
                                        Sangat Kuat</option>
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
                                {{ isset($item->waktu_mulai, $item->waktu_selesai) 
                                    ? \Carbon\Carbon::parse($item->waktu_mulai)->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai)) . ' Jam' 
                                    : '-' }}
                            </td>
                            <td class="text-center align-middle">

                                <a href="{{ asset('storage/' . $item->file_realisasi) }}" target="_blank"
                                    class="btn btn-outline-warning btn-sm">
                                    <i class="fa fa-eye"></i></a>

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
                    $k = 0;
                    @endphp
                    <tbody>
                        @foreach ($groupedDataEvaluasi as $rencanaPimpinan => $pegawaiItems)
                        @foreach ($pegawaiItems as $rencanaPegawai => $items)
                        @php
                        $rowspan = count($items); // Hitung jumlah item dalam grup
                        $umpanIndit = json_decode($evaluasi->umpan_balik, true);

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
                            <td class="align-middle text-center">{{ $item->aspek_indikator ?? '-' }}</td>
                            <td class="align-middle">{{ $item->nama_indikator ?? '-' }}</td>
                            <td class="align-middle text-center">
                                {{ $item->target_minimum ?? 0 }} - {{ $item->target_maksimum ?? 0 }}<br>
                                {{ $item->satuan ?? '-' }}
                            </td>
                            <td class="align-middle text-center">
                                <input type="text" name="realisasi[]" class="form-control"
                                    value="{{ $evaluasi->realisasi[$i++] ?? '' }}">
                            </td>
                            <td class="align-middle">
                                <input type="text" name="umpan_balik[]" class="form-control"
                                    value="{{ $umpanIndit[$k++] ?? '' }}">
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="align-middle">
                                Rating Hasil kinerja :
                            </td>
                            <td colspan="2">
                                <select style="width: 45%;" name="rating" id="" class="form-select form-select-sm">
                                    <option value="">Pilih</option>
                                    <option value="dibawah_expektasi"
                                        {{ $evaluasi->rating == 'dibawah_expektasi' ? 'selected' : '' }}>
                                        Di Bawah Expektasi
                                    </option>
                                    <option value="sesuai_expektasi"
                                        {{ $evaluasi->rating == 'sesuai_expektasi' ? 'selected' : '' }}>
                                        Sesuai Expektasi
                                    </option>
                                    <option value="diatas_expektasi"
                                        {{ $evaluasi->rating == 'diatas_expektasi' ? 'selected' : '' }}>
                                        Di Atas Expektasi
                                    </option>
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
                    @php
                    $i = 0;
                    @endphp
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
                                <select name="nilai[{{ $perilaku->id }}]" class="form-select nilai-select">
                                    <option value="">Pilih</option>
                                    <option value="dibawah_expektasi"
                                        {{ json_decode($evaluasi->nilai, true)[$loop->index] == 'dibawah_expektasi' ? 'selected' : '' }}>
                                        Di Bawah Ekspektasi
                                    </option>
                                    <option value="sesuai_expektasi"
                                        {{ json_decode($evaluasi->nilai, true)[$loop->index] == 'sesuai_expektasi' ? 'selected' : '' }}>
                                        Sesuai Ekspektasi
                                    </option>
                                    <option value="diatas_expektasi"
                                        {{ json_decode($evaluasi->nilai, true)[$loop->index] == 'diatas_expektasi' ? 'selected' : '' }}>
                                        Di Atas Ekspektasi
                                    </option>
                                </select>
                            </td>
                            <td class="align-middle">
                                <textarea name="umpan_balik_berkelanjutan[]" id="" class="form-control">
                                {{ json_decode($evaluasi->umpan_balik_berkelanjutan, true)[$loop->index] ?? '' }}
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
                                <input type="text" id="rating_perilaku" class="form-control" readonly>
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
                        <input style="width: 40%;" type="text" readonly class="form-control"
                            value="{{ $evaluasi->jumlah_periode ?? '' }}">
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
                                <option value="selesai" {{ $evaluasi->status == 'selesai' ? 'selected' : '' }}>Approve
                                </option>
                                <option value="revisi" {{ $evaluasi->status == 'revisi' ? 'selected' : '' }}>Revisi
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                            <label for="">
                                <small>Tanggal Terbit</small>
                            </label>
                            <input style="width: 50%;" type="date" name="tanggal_terbit" id="" class="form-control"
                                value="{{ \Carbon\Carbon::parse($evaluasi->tanggal_terbit)->format('Y-m-d') ?? '' }}">
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
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>

<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}");
@endif

document.addEventListener("DOMContentLoaded", function () {
    // Map nilai ke angka
    const nilaiMap = {
        dibawah_expektasi: 1,
        sesuai_expektasi: 2,
        diatas_expektasi: 3,
    };

    // Map angka rata-rata ke teks
    const averageTextMap = (average) => {
        if (average < 1.5) {
            return "Di Bawah Ekspektasi";
        } else if (average <= 2.5) {
            return "Sesuai Ekspektasi";
        } else {
            return "Di Atas Ekspektasi";
        }
    };

    // Targetkan semua dropdown dengan class `nilai-select`
    const dropdowns = document.querySelectorAll(".nilai-select");

    // Fungsi untuk menghitung rata-rata
    const calculateAverage = () => {
        let total = 0;
        let count = 0;

        dropdowns.forEach((dropdown) => {
            const value = dropdown.value;
            if (nilaiMap[value] !== undefined) {
                total += nilaiMap[value];
                count++;
            }
        });

        if (count > 0) {
            const average = total / count;
            document.getElementById("rating_perilaku").value = averageTextMap(average); // Konversi ke teks
        } else {
            document.getElementById("rating_perilaku").value = ""; // Kosongkan jika tidak ada nilai
        }
    };

    // Tambahkan event listener ke setiap dropdown
    dropdowns.forEach((dropdown) => {
        dropdown.addEventListener("change", calculateAverage);
    });

    // Hitung rata-rata saat halaman dimuat (untuk nilai default dari array)
    calculateAverage();
});

</script>
@endpush