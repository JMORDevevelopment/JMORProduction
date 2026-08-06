@php
    $hasChildren = $item->childrenRecursive->isNotEmpty();
    $isDesktopMega = ($desktop ?? false) && $depth === 0 && $hasChildren;
    $itemUrl = \Illuminate\Support\Facades\Route::has($item->url) ? route($item->url) : url($item->url);
@endphp

<li
    class="nav-item{{ !$isDesktopMega && $hasChildren ? ' dropdown' : '' }}{{ $depth > 0 && $hasChildren ? ' dropdown-submenu' : '' }}"
    @if ($isDesktopMega) data-megamenu="{{ $item->id }}"@endif>
    <a href="{{ $itemUrl }}"
        class="{{ $depth === 0 ? 'jm-nav-link' : 'dropdown-item jm-dropdown-item' }}{{ !$isDesktopMega && $hasChildren ? ' dropdown-toggle' : '' }}"
        @if ($hasChildren && !$isDesktopMega) id="navbarDropdownMenuLink-{{ $item->id }}"
           data-bs-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false" @endif>
        {{ $item->title }}
        @if ($hasChildren && $depth === 0)
            <span class="dropdown-arrow">&#9660;</span>
        @endif
    </a>

    @if ($hasChildren && !$isDesktopMega)
        <ul class="dropdown-menu jm-dropdown-menu" aria-labelledby="navbarDropdownMenuLink-{{ $item->id }}">
            @foreach ($item->childrenRecursive as $child)
                @include('partials.navigation', ['item' => $child, 'depth' => $depth + 1, 'desktop' => $desktop ?? false])
            @endforeach
        </ul>
    @endif
</li>
