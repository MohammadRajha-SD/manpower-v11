<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user mr-2"></i> {{trans('lang.user_about_me')}}</h3>
                    </div>
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img src="{{user_image(auth()->user())}}" class="profile-user-img img-fluid img-circle"
                                alt="{{auth()->user()->name}}">
                        </div>
                        <h3 class="profile-username text-center">{{auth()->user()->name}}</h3>
                        <p class="text-muted text-center">{{ auth()->user()->is_admin === 1 ? 'admin' : '' }}</p>
                        <a class="btn btn-outline-{{setting('theme_color')}} btn-block"
                            href="mailto:{{auth()->user()->email}}"><i
                                class="fas fa-envelope mr-2"></i>{{auth()->user()->email}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="clearfix"></div>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <ul class="nav nav-tabs d-flex flex-row align-items-start card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                        class="fas fa-cog mr-2"></i>{{trans('lang.app_setting')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{$slot}}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>