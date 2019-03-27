<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    @hasSection ('title')
        @yield('title')
    @else
     {{ config('app.name') }}
    @endif
  </title>
  
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
  <link rel="stylesheet" href="/admin/css/main.css?b{{config('app.build')}}" />
</head>

<body class="app-body" style="">
<div class="header header-fixed" style="position: sticky;top: 0;z-index:10;">
  <div class="header-inner row -gapless" style="">

    <div class="col-md-2">
        <div class="logo">
          <a class="logo__link" href="{{ config('cms.admin_uri') }}">{{ config('app.name') }}</a>
          <div class="nav-trigger">Меню<span></span></div>
        </div>
        
    </div>
    
  </div>
</div>
<div class="container app-content" style="background:url(/images/main-bg-light.jpg) no-repeat; background-size:cover">
    @yield('content')
</div>

<footer class="app-footer text-center">
  <small>&copy; 2019 Made by <a href="http://bit55.ru/" target="_blank">bit55</a></small>
</footer>


<link rel="stylesheet" href="/css/fontawesome.min.css" />
<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
<script src="/admin/js/bundle.js"></script>
<script src="/admin/js/common.js"></script>

</body>
</html>