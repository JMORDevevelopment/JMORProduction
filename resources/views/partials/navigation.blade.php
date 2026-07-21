{{--
    Recursive menu partial.
    Include the top level from the layout like:
        <ul class="navbar-nav">
            @foreach ($navigation as $item)
                @include('partials.navigation', ['item' => $item, 'depth' => 0])
            @endforeach
        </ul>

    This single partial replaces the CI header's 4 manually-nested for-loops
    (main_nav -> sub -> sub_child -> sub_child_menu) by calling itself.
--}}

@php
    $hasChildren = $item->childrenRecursive->isNotEmpty();
@endphp

<li class="{{ $depth === 0 ? ($hasChildren ? 'nav-item dropdown' : 'nav-item') : ($hasChildren ? 'dropdown-submenu' : 'dropdown-item') }}">
    <a
        href="{{ url($item->url) }}"
        class="{{ $depth === 0
            ? ($hasChildren ? 'nav-link dropdown-toggle' : 'nav-link')
            : ($hasChildren ? 'dropdown-item dropdown-toggle' : '') }}"
        @if ($depth === 0)
            style="color:#fff!important;"
        @endif
        @if ($hasChildren)
            id="navbarDropdownMenuLink-{{ $item->id }}"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        @endif
    >
        {{ $item->title }}
    </a>
 
    @if ($hasChildren)
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink-{{ $item->id }}">
            @foreach ($item->childrenRecursive as $child)
                @include('partials.navigation', ['item' => $child, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>