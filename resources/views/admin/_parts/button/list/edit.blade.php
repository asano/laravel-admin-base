@php
    // 一覧アイテム 編集ボタン
    $_href = $_href ?? '#';
    $_add_class = $_add_class ?? '';
@endphp

@include ('admin._parts.button.base_anchor', [
    '_href'      => $_href,
    '_label'     => '編集',
    '_color'     => 'primary',
    '_size'      => 'xs',
    '_icon'      => 'far fa-edit',
    '_add_class' => $_add_class,
])

@php
    unset($_href, $_add_class);
@endphp
