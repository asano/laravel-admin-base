@php
    $_input = $_input ?? $input;
    $_label = $_label ?? 'ラベル';
    $_id = $_id ?? $_name;
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_help = $_help ?? '';
    $_value_key = $_value_key ?? 'value';
    $_label_key = $_label_key ?? 'label';
    // 全サイズで1列使用する前提
@endphp

<div class="form-group row">
    <label class="col-md-2 col-form-label col-form-label-sm">{{ $_label }}</label>
    <div class="col-md-10">
        @foreach ($_configs as $_val => $_config)
            <div class="custom-control custom-checkbox custom-control-inline{{ $errors->has($_name.'*') ? ' is-invalid': '' }}">
                <input type="checkbox" id="{{ $_id }}_{{ $_config[$_value_key] }}" name="{{ $_name }}[]" class="custom-control-input {{ $errors->has($_name.'*') ? ' is-invalid': '' }}" value="{{ $_config[$_value_key] }}" {{ is_array($_input[$_name] ?? null) && in_array($_config[$_value_key], $_input[$_name]) ? ' checked' : '' }}>
                <label for="{{ $_id }}_{{ $_config[$_value_key] }}" class="custom-control-label">{{ $_config[$_label_key] }}</label>
            </div>
        @endforeach
        <div class="invalid-feedback">
            <strong>{{ $errors->first($_name.'*') }}</strong>
        </div>
        @if ($_help)
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{ $_help }}
            </small>
        @endif
    </div>
</div>

@php
    unset($_input, $_label, $_id, $_attrs, $_help, $_value_key, $_label_key);
@endphp
