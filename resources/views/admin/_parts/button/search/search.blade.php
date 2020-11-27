{{-- 一覧共通 検索ボタン --}}
@include ('admin._parts.button.base_button', [
    '_id'        => 'btn-submit-search',
    '_name'      => 'search',
    '_label'     => '検索',
    '_color'     => 'secondary',
    '_size'      => 'sm',
    '_icon'      => 'fas fa-search',
    '_add_class' => '',
])
