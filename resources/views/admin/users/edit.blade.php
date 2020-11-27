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
                    <div class="card-header">登録</div>
                    <div class="card-body">
                        <form method="post" id="form-update" action="{{ route('admin.users.update', ['user' => $indata->id]) }}">
                            @method('PATCH')
                            @include ('admin.users._input', ['_is_edit' => 1])
                            @csrf
                        </form>
                    </div>
                    <div class="card-footer clearfix">
                        {{-- 更新ボタン --}}
                        @include ('admin._parts.button.save', [
                            '_name' => 'update'
                        ])
                        @include ('admin._parts.button.return', [
                            '_label'     => '一覧に戻る',
                            '_href'      => route('admin.users.index'),
                            '_add_class' => ' float-right'
                        ])
                        @include ('admin._parts.button.return', [
                            '_label'     => '詳細に戻る',
                            '_href'      => route('admin.users.show', ['user' => $indata->id]),
                            '_add_class' => ' float-right mr-1'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

