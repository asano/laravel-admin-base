@php
    $_indata = $_indata ?? $indata;
    $_label = $_label ?? 'ラベル';
    $_type = $_type ?? 'input';
    $_id = $_id ?? $_name;
    $_ph = $_ph ?? '';
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_help = $_help ?? '';

    // 配列対応
    $_error_name = $_error_name ?? $_name;
    $_value = $_value ?? old($_name, $_indata->$_name ?? $_indata[$_name] ?? null);
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>
    <input type="{{ $_type }}" id="{{ $_id }}" name="{{ $_name }}" class="form-control form-control-sm{{ $errors->has($_error_name) ? ' is-invalid': '' }}" value="{{ $_value }}" placeholder="{{ $_ph }}"{{ $_attrs }}>
    <div class="invalid-feedback">
        <strong>{{ $errors->first($_error_name) }}</strong>
    </div>
    @if ($_help)
        <small id="passwordHelpBlock" class="form-text text-muted">
            {{ $_help }}
        </small>
    @endif
</div>
@php
    unset($_indata, $_label, $_type, $_id, $_ph, $_attrs, $_help, $_error_name, $_value);
@endphp
