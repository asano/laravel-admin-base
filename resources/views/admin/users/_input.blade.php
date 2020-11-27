@php
    $_is_edit = $_is_edit ?? 0;
@endphp

<div class="row">
    <div class="col-sm-6">
        @include (
            'admin._parts.form.input', [
                '_name'  => 'name',
                '_label' => '名前',
            ]
        )
    </div>
    <div class="col-sm-6">
        @include (
            'admin._parts.form.input', [
                '_name'  => 'email',
                '_label' => 'メールアドレス',
                '_type'  => 'email',
                '_ph'    => 'xxx@xxx.com'
            ]
        )
    </div>
    <div class="col-sm-6">
        @include (
            'admin._parts.form.input', [
                '_name'  => 'password',
                '_label' => 'パスワード',
                '_type'  => 'password',
                '_help'  => '4文字以上'.($_is_edit ? '、更新不要の場合は空白' : '')
            ]
        )
    </div>
    <div class="col-sm-6">
        @include (
            'admin._parts.form.toggle', [
                '_name'  => 'is_enabled',
                '_label' => '状態',
                '_check_label' => '有効',
            ]
        )
    </div>
</div>

@php
    unset($_is_edit);
@endphp
