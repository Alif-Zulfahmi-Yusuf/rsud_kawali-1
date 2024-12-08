@extends('backend.layouts.app')

@section('title', 'Perilaku Kerja')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Catatan Perilaku Kerja</h2>
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

<div class="card shadow border rounded-lg mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                <a class="btn btn-outline-success" href="#" data-bs-toggle="modal" data-bs-target="#addPerilakuModal">
                    <i class="fa fa-plus me-1"></i> Add Perilaku
                </a>
            </div>
            <table id="tablePerilaku" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">PERILAKU KERJA / BEHAVIOUR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr class="group">
                        <td colspan="5" class="fw-bold bg-light">{{ $category->name }}</td>
                    </tr>
                    @foreach ($category->perilakus as $perilaku)
                    <tr data-uuid="{{ $perilaku->uuid }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $perilaku->name }}</td>
                        <td class="text-center">
                            <div class="btn-reveal-trigger position-static">
                                <button
                                    class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                    type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-10"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editPerilakuModal"
                                        onclick="openEditModal('{{ $perilaku->uuid }}', '{{ $perilaku->category_perilaku_id }}', '{{ $perilaku->name }}')">
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-uuid="{{ $perilaku->uuid }}">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('backend.perilaku._modalAdd')
@include('backend.perilaku._modalEdit')
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/perilaku.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError({
    errors: {
        message: "{{ session('error') }}"
    }
});
@endif
</script>
@endpush