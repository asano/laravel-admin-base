@php
    $_indata = $_indata ?? $indata;
    $_label = $_label ?? 'ラベル';
    $_type = $_type ?? 'input';
    $_id = $_id ?? $_name;
    $_ph = $_ph ?? '';
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_help = $_help ?? '';
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>
    <input type="{{ $_type }}" id="{{ $_id }}" name="{{ $_name }}" class="form-control form-control-sm{{ $errors->has($_name) ? ' is-invalid': '' }}" value="{{ old($_name, $_indata->$_name ?? $_indata[$_name] ?? null) }}" placeholder="{{ $_ph }}"{{ $_attrs }}>
    <div class="invalid-feedback">
        <strong>{{ $errors->first($_name) }}</strong>
    </div>
    @if ($_help)
        <small id="passwordHelpBlock" class="form-text text-muted">
            {{ $_help }}
        </small>
    @endif
</div>
@php
    unset($_indata, $_label, $_type, $_id, $_ph, $_attrs, $_help);
@endphp
