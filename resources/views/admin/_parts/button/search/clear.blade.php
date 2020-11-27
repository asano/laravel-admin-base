{{-- 一覧共通 クリアボタン --}}
@include ('admin._parts.button.base_button', [
    '_id'        => 'btn-clear-search',
    '_name'      => 'search',
    '_label'     => 'クリア',
    '_color'     => 'warning',
    '_size'      => 'sm',
    '_icon'      => 'fas fa-eraser',
    '_add_class' => '',
])
