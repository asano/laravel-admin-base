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
                    <div class="card-header">一括登録
                        <div class="float-right pt-1 small">
                            <a href="{{ route('admin.users.import.sample_download') }}">サンプルをダウンロードする</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" id="form-confirm" action="{{ route('admin.users.import.confirm')}}" enctype="multipart/form-data">
                        @include ('admin.users.import._input')
                        @csrf
                        </form>
                    </div>
                    <div class="card-footer clearfix">
                        {{-- 登録ボタン --}}
                        @include ('admin._parts.button.save', [
                            '_name' => 'confirm'
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
@stop
