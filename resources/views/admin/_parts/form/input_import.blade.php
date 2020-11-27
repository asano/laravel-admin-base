@php
    $_indata = $_indata ?? $indata;
    $_label = $_label ?? 'ラベル';
    $_id = $_id ?? $_name;
    $_ph = $_ph ?? 'Choose file';
    $_attrs = isset($_attrs) ? ' '.$_attrs : '';
    $_help = $_help ?? '';
    $_errorList = $_errorList ?? [];
@endphp

<div class="form-group form-group-sm">
    <label for="{{ $_id }}" class="small">{{ $_label }}</label>
    <div class="input-group{{ ($_errorList || $errors->has($_name)) ? ' is-invalid': '' }}">
        <div class="custom-file">
            <input type="file" id="{{ $_id }}" name="{{ $_name }}" class="custom-file-input form-control-sm{{ ($_errorList || $errors->has($_name)) ? ' is-invalid': '' }}"{{ $_attrs }}>
            <label class="custom-file-label col-form-label-sm" for="{{ $_id }}">{{ $_ph }}</label>
        </div>
    </div>
    <div class="invalid-feedback">
        <strong>{{ $errors->first($_name) }}</strong>
    </div>
    @if($_errorList)
        <div class="invalid-feedback">
            @foreach ($_errorList as $key => $error)
                <div class="clearfix error-font">
                    データの&nbsp;<span class="row-font"> {{$key}} </span>&nbsp;行目 にエラーがあります。
                    <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#error_file_detail_toggle_{{$key}}">+ 詳細</button>
                </div>
                <div class="collapse" id="error_file_detail_toggle_{{$key}}">
                    <div class="card card-body error-detail-font">
                        @foreach($error as $key => $errorDetail)
                            <strong>{{ $errorDetail }}</strong>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@php
    unset($_indata, $_label, $_id, $_ph, $_attrs, $_help, $_errorList);
@endphp
