@php
    $_cls = isset($_configs[$_value], $_configs[$_value]['cls']) ? $_configs[$_value]['cls'] : false;
    $_label = isset($_configs[$_value], $_configs[$_value]['label']) ? $_configs[$_value]['label'] : false;
@endphp

@if ($_cls && $_label)
    <span class="badge bg-{{ $_cls }}">{{ $_label }}</span>
@endif
