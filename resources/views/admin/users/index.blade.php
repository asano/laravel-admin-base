@extends('adminlte::page')

@section('title', 'Users')

@push('css')
    <link href="{{ asset('css/admin/form.css') }}" rel="stylesheet" type="text/css">
{{--
    <link href="{{ asset('css/admin/index.css') }}" rel="stylesheet" type="text/css">
--}}
@endpush

@push('js')
    <script src="{{ asset('js/admin/form.js') }}"></script>
    <script src="{{ asset('js/admin/modal.js') }}"></script>
@endpush

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @include('admin._parts.success-message')
                <div class="card">
                    <div class="card-header">検索</div>
                    <div class="card-body">
                        <form method="get" id="form-search" action="{{ route('admin.users.index') }}" class="row">
                            <div class="col-md-6">
                                @include ('admin._parts.form.search.input',[
                                    '_label' => '名前',
                                    '_name'  => 'name',
                                ])
                            </div>
                            <div class="col-md-6">
                                @include ('admin._parts.form.search.input',[
                                    '_label' => 'メールアドレス',
                                    '_name'  => 'email',
                                ])
                            </div>
                            <div class="col-12">
                                @include ('admin._parts.form.search.check',[
                                    '_label'   => '状態',
                                    '_configs' => $configs['common']['is_enabled'],
                                    '_name'    => 'is_enabled',
                                ])
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                {{-- ボタンのセット --}}
                                @include ('admin._parts.button.search')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            一覧
                        </h3>
                        @if ($items->count())
                            <div class="card-tools">
                                {{ $items->appends(request()->all())->links('pagination::bootstrap-4-sm', ['_add_class' => ' float_right']) }}
                            </div>
                        @endif
                    </div>
                    <div class="card-body table-responsive p-2">
                        <table class="table table-hover text-nowrap small mb-2">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center align-top">
                                        <div class="form-check">
                                            <input type="checkbox" id="check-all-change-status" name="check-all-change-status" value="1" class="form-check-input">
                                        </div>
                                    </th>
                                    <th scope="col">名前</th>
                                    <th scope="col">メールアドレス</th>
                                    <th scope="col" class="text-center">状態</th>
                                    <th scope="col" class="text-center">最終ログイン日時</th>
                                    <th scope="col" class="text-center">#Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input type="checkbox" id="change-status_{{ $item->id }}" name="change-status[]" value="{{ $item->id }}" class="form-check-input">
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                        <td class="text-center">
                                            @include(
                                                'admin._parts.label',
                                                [
                                                    '_configs' => $configs['common']['is_enabled'],
                                                    '_value'   => $item->is_enabled
                                                ]
                                            )
                                        </td>
                                        <td class="text-center">
                                            {{ optional($item->last_login_at)->format('Y/m/d H:i') ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{-- 詳細ボタン --}}
                                            @include ('admin._parts.button.list.show', [
                                                '_href' => route('admin.users.show', ['user' => $item->id]),
                                            ])
                                            {{-- 編集ボタン --}}
                                            @include ('admin._parts.button.list.edit', [
                                                '_href' => route('admin.users.edit', ['user' => $item->id]),
                                            ])
                                        </td>
                                    </tr>
                                @empty
                                    {{-- 0件 --}}
                                    @include ('admin._parts.table-empty')
                                @endforelse
                            </tbody>
                        </table>
                        @if ($items->count())
                            <div class="clearfix">
                                <div class="float-right">
                                    {{ $items->appends(request()->all())->links('pagination::bootstrap-4-sm', ['_add_class' => ' m-0']) }}
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($items->count())
                        <div class="card-footer">
                            <button type="button" id="btn-open-modal-batch-disable" class="btn btn-sm btn-danger" data-is-batch="1" data-target-id-prefix="change-status_">
                                <i class="fas fa-ban"></i>&nbsp;
                                一括無効
                            </button>
                            <button type="button" id="btn-open-modal-batch-enable" class="btn btn-sm btn-success" data-is-batch="1" data-target-id-prefix="change-status_">
                                <i class="fas fa-check-circle"></i>&nbsp;
                                一括有効
                            </button>
                            <form method="post" id="form-batch-disable" action="{{ route('admin.users.disable') }}">
                                @csrf
                            </form>
                            <form method="post" id="form-batch-enable" action="{{ route('admin.users.enable') }}">
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 有効モーダル --}}
    @include(
        'admin._parts.modal', [
            '_id' => 'modal-batch-disable',
            '_color' => 'danger',
            '_title' => '確認',
            '_message' => 'チェックを付けたデータを無効に変更します。よろしいですか？',
            '_execButtonId' => 'btn-submit-batch-disable',
            '_execButtonText' => '無効化',
        ]
    )
    {{-- 削除モーダル --}}
    @include(
        'admin._parts.modal', [
            '_id' => 'modal-batch-enable',
            '_color' => 'success',
            '_title' => '確認',
            '_message' => 'チェックを付けたデータを有効に変更します。よろしいですか？',
            '_execButtonId' => 'btn-submit-batch-enable',
            '_execButtonText' => '有効化',
        ]
    )
@endsection

