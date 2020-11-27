@php
    $_input = $_input ?? $input;
    $_label = $_label ?? 'ラベル';
    $_type = $_type ?? 'input';
    $_id = $_id ?? $_name;
    $_ph = $_ph ?? '';
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_help = $_help ?? '';
@endphp

<div class="form-group row">
    <label class="col-md-4 col-form-label col-form-label-sm">{{ $_label }}</label>
    <div class="col-md-8">
        <input type="{{ $_type }}" id="{{ $_id }}" name="{{ $_name }}" class="form-control form-control-sm{{ $errors->has($_name) ? ' is-invalid': '' }}" value="{{ $_input[$_name] ?? '' }}" placeholder="{{ $_ph }}"{{ $_attrs }}>
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
    unset($_input, $_label, $_type, $_id, $_ph, $_attrs, $_help);
@endphp
