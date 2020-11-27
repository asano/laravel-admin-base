@php
    // 一覧アイテム 詳細ボタン
    $_href = $_href ?? '#';
    $_add_class = $_add_class ?? '';
@endphp

@include ('admin._parts.button.base_anchor', [
    '_href'      => $_href,
    '_label'     => '詳細',
    '_color'     => 'info',
    '_size'      => 'xs',
    '_icon'      => 'far fa-file',
    '_add_class' => $_add_class,
])

@php
    unset($_href, $_add_class);
@endphp
