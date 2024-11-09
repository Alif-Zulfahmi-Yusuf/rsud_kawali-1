@extends('backend.layouts.app')

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Users List</h2>
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
            data-list="{&quot;valueNames&quot;:[&quot;name&quot;,&quot;email&quot;,&quot;role&quot;],&quot;page&quot;:5,&quot;pagination&quot;:true}">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                @can('user-create')
                <a class="btn btn-success" href="{{ route('users.create') }}">
                    <i class="fa fa-plus me-1"></i>Create New User
                </a>
                @endcan

                <form class="position-relative">
                    <input class="form-control search-input search form-control-sm" type="search" placeholder="Search"
                        aria-label="Search">
                </form>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm fs-9 mb-0">
                    <thead>
                        <tr>
                            <th class="sort border-top border-translucent ps-3" data-sort="name">Name</th>
                            <th class="sort border-top" data-sort="email">Email</th>
                            <th class="sort border-top" data-sort="role">Role</th>
                            <th class="sort text-end align-middle pe-0 border-top" scope="col">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($data as $key => $user)
                        <tr>
                            <td class="align-middle ps-3 name">{{ $user->name }}</td>
                            <td class="align-middle email">{{ $user->email }}</td>
                            <td class="align-middle role">
                                @foreach($user->getRoleNames() as $v)
                                <span class="badge bg-success">{{ $v }}</span>
                                @endforeach
                            </td>
                            <td class="align-middle white-space-nowrap text-end pe-0">
                                <div class="btn-reveal-trigger position-static">
                                    <button
                                        class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                        type="button" data-bs-toggle="dropdown" data-boundary="window"
                                        aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><svg
                                            class="svg-inline--fa fa-ellipsis fs-10" aria-hidden="true"
                                            focusable="false" data-prefix="fas" data-icon="ellipsis" role="img"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor"
                                                d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                            </path>
                                        </svg>
                                        <!-- <span class="fas fa-ellipsis-h fs-10"></span> Font Awesome fontawesome.com --></button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">

                                        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">View</a>

                                        @can('user-edit')
                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                        @endcan

                                        <div class="dropdown-divider"></div>
                                        @can('user-delete')

                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-3"><span class="d-none d-sm-inline-block"
                    data-list-info="data-list-info">1 to 5 <span class="text-body-tertiary"> Items of </span>43</span>
                <div class="d-flex">
                    <button class="page-link disabled" data-list-pagination="prev" disabled=""><svg
                            class="svg-inline--fa fa-chevron-left" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="chevron-left" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z">
                            </path>
                        </svg><!-- <span class="fas fa-chevron-left"></span> Font Awesome fontawesome.com --></button>
                    <ul class="mb-0 pagination">
                        <li class="active"><button class="page" type="button" data-i="1" data-page="5">1</button></li>
                        <li><button class="page" type="button" data-i="2" data-page="5">2</button></li>
                        <li><button class="page" type="button" data-i="3" data-page="5">3</button></li>
                        <li class="disabled"><button class="page" type="button">...</button></li>
                    </ul>
                    <button class="page-link pe-0" data-list-pagination="next"><svg
                            class="svg-inline--fa fa-chevron-right" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z">
                            </path>
                        </svg><!-- <span class="fas fa-chevron-right"></span> Font Awesome fontawesome.com --></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {!! $data->links('pagination::bootstrap-5') !!}
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
@endpush