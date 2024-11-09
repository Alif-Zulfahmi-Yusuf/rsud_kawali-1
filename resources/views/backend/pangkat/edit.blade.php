@extends('backend.layouts.app')

@section('title', 'edit')



@section('content')

<h1>edit</h1>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
@endpush