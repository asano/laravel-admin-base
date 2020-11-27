@php
    // モーダルのID（ボタン側の）
    $_id = $_id ?? 'modal';
    // info.danger,warning,primary,default...
    $_color = $_color ?? 'default';
    // モーダルタイトル
    $_title = $_title ?? 'タイトル';
    // モーダル本文
    $_message = $_message ?? 'タイトル';
    // ボタンのID
    $_execButtonId = $_execButtonId ?? $_id.'_exec_button';
    // ボタンのテキスト
    $_execButtonText = $_execButtonText ?? '保存';
@endphp

<div class="modal fade" id="{{ $_id }}" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content bg-{{ $_color }}">
            <div class="modal-header">
                <h4 class="modal-title">{{ $_title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ $_message }}</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">閉じる</button>
                <button type="button" id="{{ $_execButtonId }}" class="btn btn-outline-light">{{ $_execButtonText }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
