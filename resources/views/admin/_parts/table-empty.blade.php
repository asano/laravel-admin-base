@php
    $_col_span = $_col_span ?? 99;
@endphp
<tr class="bg-info">
    <td class="text-center" colspan="{{ $_col_span }}">
        <i class="fas fa-search"></i>&nbsp;
        表示するデータがありません。
    </td>
</tr>
@php
    unset($_col_span);
@endphp
