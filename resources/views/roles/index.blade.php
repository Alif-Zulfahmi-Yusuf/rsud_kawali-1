@extends('backend.layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Role List</h2>
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
                @can('role-create')
                <a class="btn btn-phoenix-success me-1 mb-1" href="{{ route('roles.create') }}">
                    <i class="fa fa-plus me-1"></i>Create New Role
                </a>
                @endcan
            </div>
            <table id="tableRoles" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($roles as $key => $role)
                    <tr data-id="{{ $role->id }}">
                        <td class="align-middle ps-3 name">{{ $role->name }}</td>
                        <td>
                            <div class="btn-reveal-trigger position-static">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <!-- Icon Ellipsis -->
                                    <svg class="fs-10" aria-hidden="true" focusable="false"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
                                        <path fill="currentColor"
                                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">

                                    <a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">View</a>

                                    @can('role-edit')
                                    <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                    @endcan

                                    <div class="dropdown-divider"></div>
                                    @can('role-delete')

                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-id="{{ $role->id }}">Delete</button>
                                    @endcan
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

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/role.js') }}"></script>

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