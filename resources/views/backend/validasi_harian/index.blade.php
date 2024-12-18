@extends('backend.layouts.app')

@section('title', 'Validasi Harian')


@push('css')
<!-- select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Review Realisasi Harian</h2>
    </div>
    <div class="col-md-3 col-auto">
        <form method="GET" action="{{ route('validasi-harian.index') }}" class="d-flex align-items-end">
            <div class="me-2">
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
                    <!-- Tambahkan bulan lainnya -->
                </select>
            </div>
            <div class="me-2">
                <select id="tahun" name="tahun" class="form-control">
                    <option value="">Semua Tahun</option>
                    @for ($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card shadow rounded-lg mb-4">
        <div class="card-header">
            <h4>Daftar Review Form Harian</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm fs-9 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">NIP / Nama</th>
                            <th class="text-center" width="7%">GOL</th>
                            <th class="text-center">JABATAN</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatanHarianList as $kegiatanHarian)
                        <tr data-uuid="{{ $kegiatanHarian->uuid }}">
                            <td class="text-center align-middle">
                                {{ $kegiatanHarian->user->name }}
                                <br>
                                {{ $kegiatanHarian->user->nip }}
                            </td>
                            <td class="text-center">{{ $kegiatanHarian->user->pangkat->name ?? '-' }}</td>
                            <td class="text-center">{{ $kegiatanHarian->user->jabatan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-phoenix-warning me-1 mb-1" data-bs-toggle="modal"
                                    data-bs-target="#modalEditHarian"
                                    onclick="editDataHarianByUser ('{{ $kegiatanHarian->user->id }}')">
                                    <li class="fa fa-edit"></li>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data yang tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

<form action="{{ route('validasi-harian.update', '') }}" method="post" enctype="multipart/form-data"
    id="formEditHarian">
    @csrf
    @method('PUT')

    <div class="modal fade" id="modalEditHarian" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalEditHarianLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Validasi Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm fs-9 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Uraian</th>
                                    <th class="text-center">Output</th>
                                    <th class="text-center">Waktu</th>
                                    <th class="text-center">Evidance</th>
                                    <th class="text-center">Penilaian</th>
                                </tr>
                            </thead>
                            <tbody id="modalTableBody">
                                <!-- Data akan diisi dengan AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/kegiatan_harian.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}");
@endif

$(document).ready(function() {
    $('#bulan').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih opsi",
    });
});

$(document).ready(function() {
    $('#tahun').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih opsi",
    });
});

function editDataHarianByUser(userId) {
    // AJAX request untuk mengambil data kegiatan harian
    $.ajax({
        url: `/validasi-harian/user/${userId}`,
        method: 'GET',
        success: function(response) {
            let tableBody = '';

            // Tambahkan header dengan checkbox "Pilih Semua Logis"
            tableBody += `
                <tr>
                    <td colspan="6" class="text-center"><strong>Pilihan untuk Semua sebagai Logis</strong></td>
                    <td class="text-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pilih-semua-logis">
                            <label class="form-check-label" for="pilih-semua-logis">Centang Semua Logis</label>
                        </div>
                    </td>
                </tr>
            `;

            // Iterasi data dan buat baris tabel
            response.forEach((item, index) => {
                tableBody += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${new Date(item.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit' })}</td>
                        <td class="text-center">${item.uraian}</td>
                        <td class="text-center">${item.output}</td>
                        <td class="text-center">${item.waktu_mulai} - ${item.waktu_selesai}</td>
                        <td class="text-center">
                            <a href="${item.evidence}" target="_blank" class="btn btn-link btn-sm">Lihat File</a>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input logis-checkbox" type="checkbox" 
                                       name="penilaian[${item.uuid}][logis]" value="logis" id="logis-${item.uuid}">
                                <label class="form-check-label" for="logis-${item.uuid}">Logis</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="penilaian[${item.uuid}][kurang_logis]" value="kurang_logis" id="kurang_logis-${item.uuid}">
                                <label class="form-check-label" for="kurang_logis-${item.uuid}">Kurang Logis</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="penilaian[${item.uuid}][tidak_logis]" value="tidak_logis" id="tidak_logis-${item.uuid}">
                                <label class="form-check-label" for="tidak_logis-${item.uuid}">Tidak Logis</label>
                            </div>
                        </td>
                    </tr>
                `;
            });

            // Masukkan baris tabel ke dalam modal
            $('#modalTableBody').html(tableBody);

            // Tambahkan event listener untuk checkbox "Pilih Semua Logis"
            $('#pilih-semua-logis').on('change', function() {
                const isChecked = $(this).is(':checked');

                // Centang/Uncentang semua checkbox "Logis"
                $('.logis-checkbox').prop('checked', isChecked);
            });

            // Tampilkan modal
            $('#modalEditHarian').modal('show');
        },
        error: function() {
            alert('Gagal memuat data kegiatan harian. Silakan coba lagi.');
        }
    });
}
</script>
@endpush