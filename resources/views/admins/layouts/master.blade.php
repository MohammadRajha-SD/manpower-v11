<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="UTF-8">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('css_lib')
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/'.setting(" theme_color","primary").'.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/iziToast.css')}}">

    @yield('css_custom')
    @livewireStyles
</head>

<body
    class="@if(in_array(app()->getLocale(), ['ar','ku','fa','ur','he','ha','ks'])) rtl @else ltr @endif layout-fixed {{setting('fixed_header',false) ? "
    layout-navbar-fixed" : "" }} {{setting('fixed_footer',false) ? "layout-footer-fixed" : "" }} sidebar-mini
    {{setting('theme_color')}} {{setting('theme_contrast','')}}-mode" data-scrollbar-auto-hide="l"
    data-scrollbar-theme="os-theme-dark">
    <div class="wrapper">
        <nav
            class="main-header navbar navbar-expand {{setting('nav_color','navbar-light navbar-white')}} border-bottom-0">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('/')}}" class="nav-link">{{trans('lang.dashboard')}}</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}"
                        href="{!! route('admin.notifications.index') !!}"><i class="fas fa-bell"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#"> <i class="fa fas fa-angle-down"></i> {!!
                        Str::upper(app()->getLocale()) !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach(avaiableLanguages() as $locale => $lang)
                        <a href="/locale/{{$lang['code']}}"
                            class="dropdown-item @if(app()->getLocale() == $lang['code']) active @endif">
                            <i class="fas fa-circle mr-2"></i> {{$lang['name']}}
                        </a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{user_image(auth()->user())}}" class="brand-image mx-2 img-circle elevation-2"
                            alt="User Image">
                        <i class="fa fas fa-angle-down"></i> {!! auth()->user()->username !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('admin.user.profile')}}"
                            class="dropdown-item {{setActive(['admin.user.profile'])}}"> <i
                                class="fas fa-user mr-2"></i>
                            {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" class="dropdown-item"
                                onclick="event.preventDefault();this.closest('form').submit();">
                                <i class="fas fa-envelope mr-2"></i> {{__('auth.logout')}}
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('admins.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            {{$slot}}
        </div>

        <!-- Main Footer -->
        <footer class="main-footer border-0 shadow-sm">
            <div class="float-sm-right d-none d-sm-block">
                <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
            </div>
            <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights
            reserved.
        </footer>

    </div>

    <!-- jQuery -->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>

    <script src="{{asset('vendor/bootstrap-v4-rtl/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('js/iziToast.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}

    <!-- SWEET ALERT --->
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // delete item
            $('body').on('click', '.delete-item', function(event) {
                event.preventDefault();
                let deleteUrl = $(this).attr('href');
    
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: deleteUrl,
                            success: function(data) {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: data.message,
                                        icon: "success"
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else if (data.status === 'error') {
                                    Swal.fire({
                                        title: "Can't Delete it!",
                                        text: data.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "Error",
                                    text: "Something went wrong!",
                                    icon: "error"
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            });
        
            // provider subscription
            $('body').on('click', '.generate-payment-link', function(event) {
                event.preventDefault();
                let paymentLink  = $(this).attr('href');

                $.ajax({
                    url: paymentLink,
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
                        if (data.url != '') {
                            Swal.fire({
                                title: "Payment Link",
                                html: `
                                    <p>Copy the link or send it via email.</p>
                                    <input type="text" id="swal-payment-link" class="swal2-input" value="${data.url}" readonly>
                                `,
                                icon: "info",
                                showCancelButton: true,
                                cancelButtonText: "Close",
                                showDenyButton: true,
                                denyButtonText: "Send by Email",
                                confirmButtonText: "Copy to Clipboard",
                                allowOutsideClick: false,
                                preConfirm: () => {
                                    let input = document.getElementById("swal-payment-link");
                                    input.select();
                                    document.execCommand("copy");
                                    Swal.fire("Copied!", "The payment link has been copied.", "success");
                                }
                            }).then((result) => {
                                  if (result.isDenied) {
                                    // Send email if "Send by Email" button is clicked
                                    $.ajax({
                                        url: '{{ route("admin.subscriptions.send-payment-email") }}',
                                        type: 'POST',
                                        data: {
                                            email: data.email,
                                            price: data.price,
                                            name: data.name,
                                            link: data.url,
                                            _token: '{{ csrf_token() }}' 
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire("Sent!", "The payment link has been sent via email.", "success");
                                            } else {
                                                Swal.fire("Error!", "Failed to send the email.", "error");
                                            }
                                        },
                                        error: function(xhr) {
                                            Swal.fire("Error!", "Something went wrong.", "error");
                                        }
                                    });
                                }
                            });
                        } else {
                            Swal.fire("Error", "Could not generate the payment link.", "error");
                        }
                    },
                    error: function(xhr) {
                        Swal.fire("Error", "Something went wrong!", "error");
                    }
                });
            });
        });  
    </script>

    <!-- SWEET ALERT // --->

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <script type="text/javascript">
        iziToast.error({
            title: 'Error',
            message: '{{ $error }}',
            position: 'topCenter',
        });
    </script>
    @endforeach
    @endif

    @if (session('warning'))
    <script type="text/javascript">
        iziToast.warning({
            title: 'Warning',
            message: '{{ session('warning') }}',
            position: 'topCenter',
        });
    </script>
    @endif

    @if (session('error'))
    <script type="text/javascript">
        iziToast.error({
            title: 'Error',
            message: '{{ session('error') }}',
            position: 'topCenter',
        });
    </script>
    @endif

    @if (session('success'))
    <script type="text/javascript">
        iziToast.success({
            title: 'Successful',
            message: '{{ session('success') }}',
            position: 'topCenter',
        });
    </script>
    @endif
    @stack('scripts_lib')
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('js/scripts.min.js')}}"></script>
    @stack('scripts')

     @livewireScripts
</body>
</html>