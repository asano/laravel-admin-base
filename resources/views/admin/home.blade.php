@extends('adminlte::page')

@section('title', 'Dashboard')

@push('css')
{{--
    <link href="{{ asset('css/admin/index.css') }}" rel="stylesheet" type="text/css">
--}}
@endpush

@push('js')
{{--
    <script src="{{ asset('js/admin/index.js') }}"></script>
--}}
@endpush

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ __('messages.logged_in') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
