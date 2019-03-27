<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    @hasSection ('title')
        @yield('title')
    @else
        @config('app.name')
    @endif
  </title>

  <style>
      @import url('https://fonts.googleapis.com/css?family=Roboto:400,700,400italic,700italic&subset=cyrillic');
  </style>
  <link rel="stylesheet" href="/css/main.css?b{{config('app.build')}}" />  
</head>

<body class="app-body" style="">

<div class="header header-fixed" style="position: sticky;top: 0;z-index:10;">
  <div class="header-inner row -gapless" style="">

    <div class="col-md-2">
        <div class="logo">
          <a class="logo__link" href="/">Таксопарк</a>
          <div class="nav-trigger">Меню<span></span></div>
        </div>
        
    </div>

    <ul id="responsive-nav" class="nav col-md-10 show-md">
        @if(Auth::user()->hasRole('admin'))
            <?php
            $tabs = [
                'booking_admin' => '<i class="ico left fa-calendar"></i>Бронирование',
                'cars' => '<i class="ico left fa-taxi"></i>Автомобили',
                'users' => '<i class="ico left fa-user"></i>Пользователи',
                //'log' => '<i class="ico left fa-list"></i>Журнал событий',
            ];
            ?>
            <?php foreach ($tabs as $uri=>$title):?>
              <li class="nav__item">
                <a class="nav__link<?=(Request::is($uri.'*')?' active':'')?>" href="/<?=$uri?>"><?=$title?></a>
              </li>
            <?php endforeach;?>
        
        
        <li class="nav__item dropdown">
              <a class="nav__link dropdown-toggle" data-toggle="dropdown" href="#" id="download" aria-expanded="false">
                <i class="ico left fa-cog"></i>Опции <span class="caret"></span>
              </a>
              
              <div class="dropdown-menu">
                <a class="dropdown-item" href="/settings"><i class="ico left fa-cog"></i>Настройки</a>
                <a class="dropdown-item" href="/shifts"><i class="ico left fa-clock-o"></i>Смены</a>
                <a class="dropdown-item" href="/about"><i class="ico left fa-info-circle"></i>О программе</a>
              </div>
        </li>
        @else 
            <?php
            $tabs = [
                //'booking' => '<i class="ico left fa-calendar"></i>Бронирование',
                //'booking/history' => '<i class="ico left fa-history"></i>История',
                //'booking/request_list' => '<i class="ico left fa-list"></i>Заявки',
                //'booking/request' => '<i class="ico left fa-plus"></i>Заявки',
            ];
            ?>
            <?php foreach ($tabs as $uri=>$title):?>
              <li class="nav__item">
                <a class="nav__link<?=(Request::is($uri.'*')?' active':'')?>" href="/<?=$uri?>"><?=$title?></a>
              </li>
            <?php endforeach;?>            
        @endif
        <li class="nav__item dropdown right-md">
              <a class="nav__link dropdown-toggle" data-toggle="dropdown" href="#" id="download" aria-expanded="false">
                <i class="ico left fa-user-circle-o"></i>{{ Auth::user()->name }} <span class="caret"></span>
              </a>
              
              <div class="dropdown-menu dropdown-menu-right"><!-- -->
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"><i class="ico left fa-power-off"></i>Выход</a>
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

  <link rel="stylesheet" href="/css/font-awesome.min.css" />
  <script src="/admin/js/jquery.min.js"></script>
  <script src="/admin/js/bundle.js"></script>
  <script src="/admin/js/common.js"></script>
</body>
</html>