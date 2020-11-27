@php
    $_indata = $_indata ?? $indata;
    $_list = $_list ?? [];
    $_label = $_label ?? 'ラベル';
    $_id = $_id ?? $_name;
    $_value_key = $_value_key ?? 'value';
    $_label_key = $_label_key ?? 'label';
    $_is_append_empty = $_is_append_empty ?? true;
    $_empty_text = $_empty_text ?? '選択なし';
    $_help = $_help ?? '';
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>
    <div class="p-1{{ $errors->has($_name) ? ' is-invalid': '' }}">
        @if ($_is_append_empty)
            <div class="custom-control custom-radio custom-control-inline{{ $errors->has($_name) ? ' is-invalid': '' }}">
                <input type="radio" id="{{ $_id }}_empty" name="{{ $_name }}" value="" class="custom-control-input"{{ is_null(old($_name, $_indata->$_name ?? $_indata[$_name] ?? null)) || old($_name, $_indata->$_name ?? $_indata[$_name] ?? null) === '' ? ' checked' : '' }}>
                <label for="{{ $_id }}_empty" class="custom-control-label">{{ $_empty_text }}</label>
            </div>
        @endif
        @foreach ($_list as $_item)
            <div class="custom-control custom-radio custom-control-inline{{ $errors->has($_name) ? ' is-invalid': '' }}">
                <input type="radio" id="{{ $_id }}_{{ $_item[$_value_key] }}" name="{{ $_name }}" value="{{ $_item[$_value_key] }}" class="custom-control-input"{{ old($_name, $_indata->$_name ?? $_indata[$_name] ?? null) == $_item[$_value_key] ? ' checked' : ''}}>
                <label for="{{ $_id }}_{{ $_item[$_value_key] }}" class="custom-control-label">{{ $_item[$_label_key] }}</label>
            </div>
        @endforeach
    </div>
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
    unset($_indata, $_list, $_label, $_id, $_value_key, $_label_key, $_is_append_empty, $_empty_text, $_help);
@endphp
