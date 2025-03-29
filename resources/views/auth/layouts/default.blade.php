<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{setting('app_name')}} | {{setting('app_short_description')}}
    </title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=fallback">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/iziToast.css')}}">
    @stack('js_lib')
</head>

<body class="hold-transition login-page">
    <div class="login-box" @if(isset($width)) style="width:{{$width}}" @endif>
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{appLogo()}}" alt="{{setting('app_name', 'HPower')}}">
            </a>
        </div>
        <div class="card shadow-sm">
            {{$slot}}
        </div>
    </div>
    <!-- /.login-box -->

    <script src="{{asset('js/iziToast.js')}}"></script>
    
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

    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    @stack('scripts')
</body>
</html>