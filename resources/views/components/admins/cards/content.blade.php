<style>
    .pagination>li>a {
        border-radius: 70px !important;
        font-size: 12px;
        line-height: 1;
        margin: 0 3px;
        width: 49px;
        height: 32px;
        text-align: center;
        padding: 10px 12px;
    }
</style>

@props(['name1' => null, 'name2' => null, 'route2'=> null, 'route1'=>null ,'isEditMode' => false, 'route3' => null,
'name3' => null, 'isCreateMode' => 'true'])
@php
$route_1 = $route1 != null ? route($route1) : '#';
$route_2 = $route2 != null ? route($route2) : '#';
@endphp
<div class="content">
    <div class="clearfix"></div>
    <div class="card shadow-sm">
        <div class="card-header">
            <ul class="nav nav-tabs d-flex flex-md-row flex-column-reverse align-items-start card-header-tabs">
                <div class="d-flex flex-row">
                    <li class="nav-item">
                        <a class="nav-link {{ setActive([$route1]) }}" href="{!! $route_1 !!}"><i
                                class="fa fa-list mr-2"></i>{!! $name1 !!} </a>
                    </li>
                    @if($isCreateMode)
                    <li class="nav-item ">
                        <a class="nav-link {{ setActive([$route2]) }}" href="{!! $route_2 !!}"><i
                            class="fa fa-plus mr-2"></i>{!! $name2
                            !!}
                        </a>
                    </li>
                    @endif

                    @if($isEditMode == true)
                    <li class="nav-item ">
                        <a class="nav-link {{ setActive([$route3[0]]) }}" href="{!! route($route3[0], $route3[1]) !!}"><i
                                class="fa fa-plus mr-2"></i>{!! $name3
                            !!}
                        </a>
                    </li>
                    @endif
                </div>
            </ul>
        </div>
        <div class="card-body">
            {{$slot}}
        </div>
    </div>
</div>