<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-{{setting('theme_contrast')}}-{{setting('theme_color')}} shadow">
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link border-bottom-0 {{setting('logo_bg_color','bg-white')}}">
        <img src="{{ appLogo() }}" alt="{{setting('app_name')}}" class="brand-image">
        <span class="brand-text font-weight-light">{{setting('app_name')}}</span> </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                @include('admins.layouts.menu',['icons'=>true])
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
