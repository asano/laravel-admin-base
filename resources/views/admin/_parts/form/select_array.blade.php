@php
    $_indata = $_indata ?? $indata;
    $_list = $_list ?? [];
    $_label = $_label ?? 'ラベル';
    $_type = $_type ?? 'input';
    $_id = $_id ?? $_name;
    $_value_key = $_value_key ?? 'value';
    $_label_key = $_label_key ?? 'label';
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_add_class = isset($_add_class) ? ' '.$_add_class : '';
    $_help = $_help ?? '';
    $_is_append_empty = $_is_append_empty ?? true;
    $_empty_text = $_empty_text ?? '-----';

    // 配列対応
    $_error_name = $_error_name ?? $_name;
    $_value = $_value ?? old($_name, $_indata->$_name ?? $_indata[$_name] ?? null);
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>

    <select id="{{ $_id}}" name="{{ $_name }}" class="form-control form-control-sm select2bs4-sm{{ $_add_class }}{{ $errors->has($_error_name) ? ' is-invalid': '' }}" style="width: 100%;"{{ $_attrs }}>
        @if ($_is_append_empty)
            <option value="">----</option>
        @endif
        @foreach ($_list as $_item)
            <option value="{{ $_item[$_value_key] }}"{{ $_value == $_item[$_value_key] ? ' selected' : ''}}>{{ $_item[$_label_key] }}</option>
        @endforeach
    </select>
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
    unset(
        $_indata,
        $_list,
        $_item,
        $_label,
        $_id,
        $_value_key,
        $_label_key,
        $_attrs,
        $_help,
        $_is_append_empty,
        $_empty_text,
        $_error_name,
        $_value
    );
@endphp
