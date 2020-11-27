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
                    <div class="card-header">一括登録 完了</div>
                    <div class="card-body">
                        <form method="post" id="form-export" action="{{ route('admin.users.import.download')}}">
                        @csrf
                        </form>
                        <p>ユーザの一括登録が完了しました。</p>
                    </div>
                    <div class="card-footer clearfix">
                        {{-- 登録ボタン --}}
                        @include ('admin._parts.button.base_button', [
                            '_id'        => 'btn-submit-export',
                            '_label'     => 'CSVエクスポート',
                            '_color'     => 'info',
                            '_size'      => 'sm',
                            '_icon'      => 'fas fa-download',
                            '_add_class' => '',
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