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
  
  <link rel="icon" type="image/png" href="/admin/images/favicon.png" sizes="16x16">
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
  <link rel="stylesheet" href="/admin/css/main.css?b{{config('app.build')}}" />
</head>

<body class="app-body" style="">

<div class="header header-fixed" style="position: sticky;top: 0;z-index:10;">
  <div class="header-inner row -gapless" style="">

    <div class="col-md-2">
        <div class="logo">
          <a class="logo__link text-danger" href="{{ config('cms.admin_uri') }}">{{ config('app.name') }}</a>
          <div class="nav-trigger">Меню<span></span></div>
        </div>
        
    </div>

    <ul id="responsive-nav" class="nav col-md-10 show-md">
        @if(Auth::user()->isAdmin())
            <?php
            $tabs = [
                'pages' => '<i class="ico left fa-sitemap"></i>Страницы',
                'users' => '<i class="ico left fa-user"></i>Пользователи',
                //'log' => '<i class="ico left fa-list"></i>Журнал событий',
            ];
            ?>
            <?php foreach ($tabs as $uri=>$title):?>
              <li class="nav__item">
                <a class="nav__link<?=(Request::is(config('cms.admin_uri').'/'.$uri.'*')?' active':'')?>" href="{{ config('cms.admin_uri') }}/<?=$uri?>"><?=$title?></a>
              </li>
            <?php endforeach;?>
        
        
        <li class="nav__item dropdown">
              <a class="nav__link dropdown-toggle" data-toggle="dropdown" href="#" id="download" aria-expanded="false">
                <i class="ico left fa-tools"></i>Опции <span class="caret"></span>
              </a>
              
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ config('cms.admin_uri') }}/settings">
                  <i class="ico left fa-cog"></i>Настройки</a>
                <a class="dropdown-item" href="{{ config('cms.admin_uri') }}/about">
                  <i class="ico left fa-info-circle"></i>О программе</a>
              </div>
        </li>
        <li class="nav__item">
          <a class="nav__link text-warning" target="_blank" href="/">
            Сайт <i class="ico right fa-external-link-square-alt"></i>
          </a>
        </li>
        @endif
        <li class="nav__item dropdown right-md">
          <a class="nav__link dropdown-toggle" data-toggle="dropdown" href="#" id="download" aria-expanded="false">
            <i class="ico left far fa-user-circle"></i>{{ Auth::user()->name }} <span class="caret"></span>
          </a>
          
          <div class="dropdown-menu dropdown-menu-right"><!-- -->
            <a class="dropdown-item" href="{{ config('cms.admin_uri') }}/profile">
                  <i class="ico left fa-id-card"></i>Профиль</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"><i class="ico left fa-sign-out-alt"></i>Выход</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
          </div>
        </li>
    </ul>
  </div>
</div>

  
@hasSection ('header')
<header class="page-header">
<div class="page-header-inner">
    @yield('header') 
</div>
</header>
@endif


<div class="container app-content">
    @yield('content')
</div>

<footer class="app-footer text-center">
  <small>&copy; 2019 Made by <a href="http://bit55.ru/" target="_blank">bit55</a></small>
</footer>


<link rel="stylesheet" href="/admin/css/fontawesome.min.css" />
<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
<script src="/admin/js/bundle.js"></script>
<script src="/admin/js/common.js"></script>

</body>
</html>