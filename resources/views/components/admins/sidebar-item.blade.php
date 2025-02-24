@props(['name', 'icon' => '', 'route' => null, 'showIcon' => true, 'activeRoutes' => [], 'prefixAdmin'=>true])

@php
$route = $route != null ? route($prefixAdmin == true ? ('admin.' . $route) : $route) : '#';
@endphp

<li class="nav-item">
    <a class="nav-link {{setActive($activeRoutes)}}" href="{{$route}}">
        @if($showIcon)<i class="nav-icon {{$icon}}"></i>@endif
        <p>{{$name}}</p>
    </a>
</li>