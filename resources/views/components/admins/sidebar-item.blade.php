{{-- <li class="nav-item">
    <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{!! url('/') !!}">@if($icons)
        <i class="nav-icon fas fa-tachometer-alt"></i>@endif
        <p>{{trans('lang.dashboard')}}</p>
    </a>
</li>


['categories*']
{!! route('categories.index') !!}

<li class="nav-item">
    <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}"
        href="{!! route('categories.index') !!}">@if($icons)
        <i class="nav-icon fas fa-folder-open"></i>@endif<p>{{trans('lang.category_plural')}}</p></a>
</li> --}}

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