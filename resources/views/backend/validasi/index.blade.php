@extends('backend.layouts.app')


@section('title', 'Review Form SKP')

@push('css')
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
@endpush





@section('content')
<div class="row gy-3 mb-4 justify-content-between align-items-center">
    <div class="col-md-9 col-auto">
        <h2 class="text-body-emphasis">Review Form SKP</h2>
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
<div class="card">
    <div class="card-header">
        <h3>Daftar Review Form SKP</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableValidasi" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Tahun</th>
                        <th>NIP / Nama</th>
                        <th>GOL</th>
                        <th>JABATAN</th>
                        <th>UNIT KERJA</th>
                        <th>TANGGAL SKP</th>
                        <th>STATUS</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($skps as $skp)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td>{{ $skp->tahun }}</td>
                        <td>
                            <p>{{ $skp->user->name ?? '-' }}</p>
                            <small>{{ $skp->user->nip ?? '-' }}</small>
                        </td>
                        <td class="text-center align-middle">{{ $skp->user->pangkat->name ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $skp->user->jabatan ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $skp->user->unit_kerja ?? '-' }}</td>
                        <td class="text-center align-middle">
                            {{ $skp->tanggal_skp ? \Carbon\Carbon::parse($skp->tanggal_skp)->format('Y M d') : '-' }}
                        </td>
                        <td class="text-center align-middle">
                            @if ($skp->status === 'pending')
                            <span class="badge badge-phoenix badge-phoenix-warning">Pending</span>
                            @elseif ($skp->status === 'approve')
                            <span class="badge badge-phoenix badge-phoenix-success">Approve</span>
                            @elseif ($skp->status === 'revisi')
                            <span class="badge badge-phoenix badge-phoenix-danger">Revisi</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            <a href="{{ route('validasi.edit', $skp->uuid) }}"
                                class="btn btn-phoenix-warning me-1 mb-1">
                                <span data-feather="check-circle"></span>
                            </a>
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
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/validasi.js') }}"></script>

@endpush