@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('header')
{{ __('Dashboard') }}
@endsection

@push('css')
<style>
.profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.card-body hr {
    margin: 1rem 0;
}

@media (max-width: 800px) {
    .echart-basic-bar-chart-example {
        min-height: 250px;
    }
}
</style>
@endpush

@section('content')

@endsection