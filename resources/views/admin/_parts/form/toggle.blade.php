@php
    $_indata = $_indata ?? $indata;
    $_label = $_label ?? 'ラベル';
    $_id = $_id ?? $_name;
    $_check_label = $_check_label ?? $_label;
    $_default_value = $default_value ?? 0;
    $_checked_value = $checked_value ?? 1;
    $_help = $_help ?? '';
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>
    <div class="pt-1{{ $errors->has($_name) ? ' is-invalid': '' }}">
        <input type="hidden" name="{{ $_name }}" value="{{ $_default_value }}">
        <div class="custom-control custom-switch custom-switch-on-success">
            <input type="checkbox" id="{{ $_id }}" name="{{ $_name }}" value="{{ $_checked_value }}" class="custom-control-input"{{ old($_name, ($_indata->$_name ?? $_indata[$_name] ?? null)) == $_checked_value ? ' checked' : '' }}>
            <label class="custom-control-label font-weight-normal" for="{{ $_id }}">{{ $_check_label }}</label>
        </div>
    </div>
    @if ($_help)
        <small id="passwordHelpBlock" class="form-text text-muted">
            {{ $_help }}
        </small>
    @endif
    <div class="invalid-feedback">
        <strong>{{ $errors->first($_name) }}</strong>
    </div>
</div>
@php
    unset($_indata, $_label, $_id, $_check_label, $_default_value, $_checked_value, $_help);
@endphp
