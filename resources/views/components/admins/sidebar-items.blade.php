@props(['routes' => null, 'showIcons' => true, 'icon' => '', 'name' => null])

@php
$isActive = $routes != null ? setActive($routes) : null;
$routes = $routes != null ? setActive($routes, 'menu-open') : null;
@endphp

<li class="nav-item has-treeview {{ $routes }}">
    <a href="#" class="nav-link {{ $isActive }}">
        @if($showIcons)
        <i class="nav-icon {{$icon}}"></i>
        @endif
        <p>{!! $name !!} <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{$slot}}
    </ul>
</li>