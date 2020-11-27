@php
    $_is_edit = $_is_edit ?? 0;
@endphp

<div class="row">
    <div class="col-sm-12">
        @include (
            'admin._parts.form.input_import', [
                '_name'      => 'file',
                '_label'     => 'ファイル',
                '_errorList' => session('errorList')
            ]
        )
    </div>
</div>

@php
    unset($_is_edit);
@endphp
