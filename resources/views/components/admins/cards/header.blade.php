@props(['route' => '#', 'name'=> null, 'desc'=>null, 'table_name' => null])

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold"> 
                    {!! $name !!} 
                    <small class="mx-3">|</small>
                    <small> {!! $desc !!} </small>
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb bg-white float-sm-right rounded-pill px-4 py-2 d-none d-md-flex">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fas fa-tachometer-alt"></i>
                            {{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{$route}}"> {!! $name !!}</a>
                    </li>
                    <li class="breadcrumb-item active">{!! $table_name !!} </li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->