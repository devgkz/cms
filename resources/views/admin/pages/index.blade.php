@extends('admin.layout')

@section('title', 'Страницы сайта'.($page ? ' - '.$page->title : ''))

@section('content')


<ul class="tabs classic topline">
    @if($id)
        @forelse($parents as $parent)
            <li class="tabs__item"><a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages/index/{{$id}}">{{ $parent->title }} &rarr;</a></li>
        @empty
            <li class="tabs__item"><a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages">Начало</a> &rarr;</li>
        @endforelse
        <li class="tabs__item"><a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages/index/{{$id}}">Список страниц</a></li>
        <li class="tabs__item"><a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages/edit/{{$id}}">Содержимое</a></li>
    @else 
        <li class="tabs__item"><a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages">Список страниц</a></li>
    @endif
</ul>

<div class="alert mb-3">
<?php
/* 
Список страниц</h1>
&nbsp;&nbsp;<a href="/admin/pages/add/<?=$id?>" class="do" title="Добавить новую страницу">добавить страницу</a>
 */
if (count($parents)) {
  echo '<p><a href="'.config('cms.admin_uri').'/pages">Начало</a> &rarr; ';
  foreach($parents as $parent) {
    if($parent['id']==$id) echo '<b>'.$parent['title'].'</b>  <span class="inline-btns"><a class="do" href="'.config('cms.admin_uri').'/pages/add/'.$id.'">добавить сюда</a> | <a class="do" href="'.$parent['slug'].'" target="_blank">просмотр</a>&nbsp;<img src="'.config('cms.admin_uri').'/images/new-window.png"></span>';
    else echo '<a href="'.config('cms.admin_uri').'/pages/index/'.$parent['id'].'">'.$parent['title'].'</a> &rarr; ';
    if($parent['id']==$id) $so = $parent['sort_childs'];
  }
  echo '</p>';
} else {
  echo '<p><b>Начало</b> &rarr; <span class="inline-btns"> <a class="do" href="'.config('cms.admin_uri').'/pages/add/'.$id.'">добавить сюда</a> </span></p>';
}
?>
</div>
<div class="table-responsive">
<table class="table bordered grid full" style="width:auto">
  <thead>
      <tr>
          <th>ID</th>
          <th>Title</th>          
          <th>Template</th>          
          <th>Created</th>          
          <th>**</th>
      </tr>
  </thead>
  <tbody>
      @forelse($items as $item)
      <tr>
          <td>{{ $item->id }}</td>
          <td><a href="{{ config('cms.admin_uri') }}/pages/index/{{$item->id}}">{{ $item->title }}</a></td>
          <td>{{ $item->template }}</td>
          <td class="hovered" style="white-space:nowrap">
            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d.m.Y H:i') }}
          </td>          
          <td class="text-right" style="white-space:nowrap">
            
          </td>
      </tr>
      @empty
        <tr><td colspan="5" class="text-xs-center">Нет страниц.</td></tr>
      @endforelse
  </tbody>
</table>

</div>
  
@endsection