@php
    // 詳細/編集/登録画面 戻るボタン
    $_href = $_href ?? '#';
    $_label = $_label ?? '戻る';
    $_add_class = $_add_class ?? '';
@endphp

@include ('admin._parts.button.base_anchor', [
    '_href'      => $_href,
    '_label'     => $_label,
    '_color'     => 'default',
    '_size'      => 'sm',
    '_icon'      => 'fas fa-arrow-left',
    '_add_class' => $_add_class,
])

@php
    unset($_href, $_label, $_add_class);
@endphp
