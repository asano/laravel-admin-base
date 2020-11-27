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
<form role="form" id="form-store" method="POST" action="{{ route('admin.users.import.save') }}">
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        一括登録 確認
                    </div>

                    <div class="card-body table-responsive p-2">
                        <table class="table table-hover text-nowrap table-sm small mb-2">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center align-middle">名前</th>
                                    <th scope="col" class="text-center align-middle">メールアドレス</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fileData as $key => $row)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $row['name'] }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $row['email'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{-- 登録ボタン --}}
                        @include ('admin._parts.button.save', [
                            '_name' => 'store'
                        ])
                        @include ('admin._parts.button.return', [
                            '_label'     => '戻る',
                            '_href'      => route('admin.users.import.create'),
                            '_add_class' => ' float-right'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop