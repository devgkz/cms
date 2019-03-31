@extends('admin/layout')

@section('header')
<div class="page-header-main text-center">
    <i class="ico left fa-lightbulb text-info"></i>Добро пожаловать в систему управления сайтом!
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <a class="card-block text-center text-muted" style="text-decoration:none" href="{{ config('cms.admin_uri') }}/pages">
                <h1><i class="ico fa-sitemap"></i></h1>
                <div>Страницы</div>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <a class="card-block text-center text-muted" style="text-decoration:none" href="{{ config('cms.admin_uri') }}/users">
                <h1><i class="ico fa-user-friends"></i></h1>
                <div>Пользователи</div>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <a class="card-block text-center text-muted" style="text-decoration:none" target="_blank" href="/">
                <h1><i class="ico fa-eye"></i></h1>
                <div>Просмотр сайта <i class="ico right fa-external-link-alt"></i></div>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <a class="card-block text-center text-muted" style="text-decoration:none" href="{{ config('cms.admin_uri') }}/settings">
                <h1><i class="ico fa-cog"></i></h1>
                <div>Настройки</div>
            </a>
        </div>
    </div>
</div>
@endsection
