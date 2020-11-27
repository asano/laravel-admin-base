@php
    // 登録/編集画面 編集ボタン
    $_name = $_name ?? 'save';
    $_id = 'btn-submit-' . $_name;
    if (!isset($_label)) {
        switch ($_name) {
            case 'store':
                $_label = '登録';
                break;
            case 'update':
                $_label = '更新';
                break;
            case 'confirm':
                $_label = '確認';
                break;
            default:
                $_label = '保存';
                break;
        }
    }
    $_color = $_color ?? 'info';
    $_size = $_size ?? 'sm';
    $_icon = $_icon ?? 'fas fa-save';
    $_add_class = $_add_class ?? '';
@endphp
@include ('admin._parts.button.base_button', [
    '_id'        => $_id,
    '_name'      => $_name,
    '_label'     => $_label,
    '_color'     => $_color,
    '_size'      => $_size,
    '_icon'      => $_icon,
    '_add_class' => $_add_class,
])
@php
    unset($_id, $_name, $_label, $_color, $_size, $_icon, $_add_class);
@endphp
