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
    <form action="">
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
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
                                <th class="text-center">Kualitas</th>
                                <th class="text-center">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->uraian_kegiatan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection


@push('js')


@endpush