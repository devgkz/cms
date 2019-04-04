@extends('admin.layout')

@section('title', 'Страницы'.($page ? ': '.$page->title : ''))

@section('header')
<div class="page-header-main">
    <div class="page-header-right">
        <a class="btn" href="{{ config('cms.admin_uri') }}/pages/add/{{ $id ?: 0 }}" onclick="return cms.ajaction(this);"><i class="ico left fa-plus"></i>Добавить</a>
    </div>

    <a class="btn" href="{{ config('cms.admin_uri') }}/pages"><i class="ico left fa-sitemap text-info"></i>Начало</a>
    @foreach($parents as $parent)
        <a class="btn" href="{{ config('cms.admin_uri') }}/pages/index/{{$parent->id}}">{{ $parent->title }}</a>
    @endforeach

    @if($page && $page->title)
        <span class="btn"><b>{{ $page->title }}</b></span>
    @endif
</div>
@endsection

@section('content')

<ul class="tabs classic topline">
    @if($id)
        <li class="tabs__item"><a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages/index/{{$id}}">Страницы</a></li>
        <li class="tabs__item"><a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages/edit/{{$id}}">Содержимое</a></li>
    @else
        <li class="tabs__item"><a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages">Страницы</a></li>
    @endif
</ul>

<div class="table-responsive">
<table class="table bordered grid full" style="width:auto">
  <thead>
      <tr>
          <th>ID</th>
          <th style="min-width:270px">Title</th>
          <th>URL</th>
          <th>Шаблон</th>
          <th>Статус</th>
          <th>Created</th>
          <th class="text-right text-muted">**</th>
      </tr>
  </thead>
  <tbody>
      @forelse($items as $item)
      <tr>
          <td><small>{{ $item->id }}</small></td>
          <td>
              <a href="{{ config('cms.admin_uri') }}/pages/index/{{$item->id}}">{{ $item->title }}</a>
              <sup class="text-muted" title="Число подстраниц">{{ $item->childs->count()?:'' }}</sup>
          </td>
          <td><small>{{ $item->slug }}</small></td>
          <td>{{ App\Models\Page::getTemplates()[$item->template] }}</td>
          <td><span class="badge {{ App\Models\PageStatusList::getCss($item->status) }}">
                {{ App\Models\PageStatusList::get($item->status) }}</span></td>
          <td style="white-space:nowrap">
            <small>{{ $item->created_at->format('d.m.Y H:i') }}</small>
          </td>
          <td class="text-right" style="white-space:nowrap">
              <a class="text-info" href="{{ config('cms.admin_uri') }}/pages/edit/{{ $item->id }}" title="Редактировать"><i class="fa fa-pen"></i></a>
              <span class="p-1"></span>
              <a class="text-muted" href="{{ $item->slug }}" target="_blank" title="Просмотр (в новом окне)"><i class="fa fa-external-link-alt"></i></a>
              <span class="p-1"></span>
              <a class="text-danger" onclick="cms.modal.confirm('Удалить страницу?', function(){document.getElementById('remove-form-{{ $item->id }}').submit();}); return false;" href="#" title="Удалить"><i class="fa fa-trash"></i></a>
              <form id="remove-form-{{ $item->id }}" action="{{ config('cms.admin_uri') }}/pages/remove/{{ $item->id }}" method="POST" style="display: none;" onsubmit="">{{ csrf_field() }}</form>
          </td>
      </tr>
      @empty
        <tr><td colspan="7" class="text-xs-center">Нет страниц</td></tr>
      @endforelse
  </tbody>
</table>

</div>

@endsection
