@extends('admin.layout')

@section('title', 'Пользователи')

@section('header')
<div class="page-header-main">
    <i class="ico left fa-user-friends text-info"></i>Пользователи
    <div class="page-header-right">
        <a class="btn" href="{{ config('cms.admin_uri') }}/users/add" onclick="return cms.ajaction(this);"><i class="ico left fa-plus"></i>Добавить пользователя</a>
    </div>
</div>
@endsection

@section('content')
  
  @include('admin.partials.alerts')
  
  <div class="table-responsive">
  <table class="table bordered grid">
      <thead class="">
          <tr>
              <!--th class="text-xs-center">ID</th-->
              <th>@sortablelink('name', 'ФИО')</th>
              <th>@sortablelink('role', 'Группа')</th>
              <th>E-mail</th>              
              <th>Телефон</th>              
              <th>Комментарий</th>
              <th>@sortablelink('created_at', 'Зарегистрирован')</th>
              <th class="text-right text-muted">*</th>
          </tr>
      </thead>
      <tbody>
          @forelse($items as $item)
          <tr>
              <!--td class="text-xs-center">
              {{ $item->id }}
              </td-->
              <td style="white-space:nowrap"><i class="ico left fa-user text-{{ App\Models\UserRoleList::getCss($item->role)?:'muted' }}"></i><a href="{{ config('cms.admin_uri') }}/users/view/{{ $item->id }}" onclick="return cms.ajaction(this);">{{ $item->name }}</a></td>
              <td style="white-space:nowrap"><span class="badge {{ App\Models\UserRoleList::getCss($item->role) }}">{{ App\Models\UserRoleList::get($item->role) }}</span></td>
              <td style="white-space:nowrap">{{ $item->email }}</td>
              <td style="white-space:nowrap">{{ $item->phone }}</td>
              <td style="white-space:nowrap">{{ $item->comment }}</td>
              
              <td style="white-space:nowrap"><small>{{ $item->created_at->format('d.m.Y H:i') }}</small></td>
              <td class="text-right" style="white-space:nowrap">
                  <a class="text-info" href="{{ config('cms.admin_uri') }}/users/edit/{{ $item->id }}" title="Редактировать" onclick="return cms.ajaction(this);"> <i class="fa fa-pen"></i></a>
                  <span class="p-1"></span>
                  <a class="text-danger" onclick="cms.modal.confirm('Удалить пользователя?', function(){document.getElementById('remove-form-{{ $item->id }}').submit();}); return false;" href="#" title="Удалить"><i class="fa fa-trash"></i></a></div>
                  <form id="remove-form-{{ $item->id }}" action="{{ config('cms.admin_uri') }}/users/remove/{{ $item->id }}" method="POST" style="display: none;" onsubmit="">{{ csrf_field() }}</form>
              </td>
          </tr>
          @empty
            <tr><td colspan="5" class="text-xs-center">Нет пользователей. <a href="{{ config('cms.admin_uri') }}/users/add" onclick="return cms.ajaction(this);">Добавить</a></td></tr>
          @endforelse
      </tbody>
  </table>
  </div>
  {{$items->links()}}
@endsection