@php
    $_indata = $_indata ?? $indata;
@endphp

<dl class="row">
    <dt class="col-lg-3 col-sm-4">
        名前
    </dt>
    <dd class="col-lg-3 col-sm-8">
        {{ $_indata->name }}
    </dd>
    <dt class="col-lg-3 col-sm-4">
        メールアドレス
    </dt>
    <dd class="col-lg-3 col-sm-8">
        {{ $_indata->email }}
    </dd>
    <dt class="col-lg-3 col-sm-4">
        状態
    </dt>
    <dd class="col-lg-3 col-sm-8">
        @include(
            'admin._parts.label',
            [
                '_configs' => $configs['common']['is_enabled'],
                '_value'   => $_indata->is_enabled
            ]
        )
    </dd>
    <dt class="col-lg-3 col-sm-4">
        最終ログイン日時
    </dt>
    <dd class="col-lg-3 col-sm-8">
        {{ optional($_indata->last_login_at)->format('Y/m/d H:i') ?? '-' }}
    </dd>
    <dt class="col-lg-3 col-sm-4">
        登録日時
    </dt>
    <dd class="col-lg-3 col-sm-8">
        {{ optional($_indata->created_at)->format('Y/m/d H:i') ?? '-' }}
    </dd>
    <dt class="col-lg-3 col-sm-4">
        更新日時
    </dt>
    <dd class="col-lg-3 col-sm-8">
        {{ optional($_indata->updated_at)->format('Y/m/d H:i') ?? '-' }}
    </dd>
</dl>

@php
    unset($_indata);
@endphp
