@php
    $_input = $_input ?? $input;
    $_list = $_list ?? [];
    $_label = $_label ?? 'ラベル';
    $_type = $_type ?? 'input';
    $_id = $_id ?? $_name;
    $_value_key = $_value_key ?? 'value';
    $_label_key = $_label_key ?? 'label';
    $_help = $_help ?? '';
    $_is_append_empty = $_is_append_empty ?? true;
    $_empty_text = $_empty_text ?? '-----';
@endphp

<div class="form-group row">
    <label class="col-md-4 col-form-label col-form-label-sm">{{ $_label }}</label>

    <div class="col-md-8">
        <select id="{{ $_id}}" name="{{ $_name }}" class="form-control form-control-sm select2bs4-sm{{ $errors->has($_name) ? ' is-invalid': '' }}" style="width: 100%;">
            @if ($_is_append_empty)
                <option value="">----</option>
            @endif
            @foreach ($_list as $_item)
                <option value="{{ $_item[$_value_key] }}"{{ ($_input[$_name] ?? '') && $_input[$_name] == $_item[$_value_key] ? ' selected' : '' }}>{{ $_item[$_label_key] }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">
            <strong>{{ $errors->first($_name) }}</strong>
        </div>
        @if ($_help)
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{ $_help }}
            </small>
        @endif
    </div>
</div>
@php
    unset($_input, $_list, $_item, $_label, $_id, $_value_key, $_label_key, $_help, $_is_append_empty, $_empty_text);
@endphp
