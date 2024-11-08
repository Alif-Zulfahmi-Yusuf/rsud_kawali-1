@extends('backend.layouts.app')

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
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card-body">
        <div id="tableExample3"
            data-list="{&quot;valueNames&quot;:[&quot;name&quot;,&quot;page&quot;:5,&quot;pagination&quot;:true}">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                @can('role-create')
                <a class="btn btn-success" href="{{ route('roles.create') }}">
                    <i class="fa fa-plus me-1"></i>Create New Role
                </a>
                @endcan

                <form class="position-relative">
                    <input class="form-control search-input search form-control-sm" type="search" placeholder="Search"
                        aria-label="Search">
                </form>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm fs-9 mb-0">
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($roles as $key => $role)
                    <tr>
                        <td class="align-middle ps-3 name">{{ $role->name }}</td>
                        <td>
                            <div class="btn-reveal-trigger position-static">
                                <button
                                    class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                    type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                    aria-expanded="false" data-bs-reference="parent"><svg
                                        class="svg-inline--fa fa-ellipsis fs-10" aria-hidden="true" focusable="false"
                                        data-prefix="fas" data-icon="ellipsis" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                        </path>
                                    </svg>
                                    <!-- <span class="fas fa-ellipsis-h fs-10"></span> Font Awesome fontawesome.com --></button>
                                <div class="dropdown-menu dropdown-menu-end py-2">

                                    <a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">View</a>

                                    @can('role-edit')
                                    <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                    @endcan

                                    <div class="dropdown-divider"></div>
                                    @can('role-delete')

                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
{!! $roles->links('pagination::bootstrap-5') !!}

@endsection