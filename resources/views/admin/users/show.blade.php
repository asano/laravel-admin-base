@extends('adminlte::page')

@section('title', 'Users')

@push('css')
    <link href="{{ asset('css/admin/form.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('js')
    <script src="{{ asset('js/admin/form.js') }}"></script>
@endpush

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">詳細</div>
                    <div class="card-body">
                        @include ('admin.users._info', ['_indata' => $indata])
                    </div>
                    <div class="card-footer clearfix">
                        @include ('admin._parts.button.edit', [
                        {{-- 編集ボタン --}}
                            '_href' => route('admin.users.edit', ['user' => $indata->id]),
                        ])
                        @include ('admin._parts.button.return', [
                            '_label'     => '一覧に戻る',
                            '_href'      => route('admin.users.index'),
                            '_add_class' => ' float-right'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

