<h1 class="mb-3"><i class="ico left fa-user text-muted"></i>{{ App\Models\UserRoleList::get($item->role) }} {{-- request()->ajax() --}}</h1>
<hr>

<div style="max-width:400px">
    <div class="row">
      <div class="col"><strong>ФИО</strong></div>
      <div class="col"><strong>{{ $item->name }}</strong></div>
    </div>
    <div class="row">
      <div class="col">E-mail</div>
      <div class="col"><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></div>
    </div>
    <div class="row">
      <div class="col">Телефон</div>
      <div class="col"><a href="tel:{{ str_replace([' ', '-', '(', ')'], '', $item->phone) }}">{{ $item->phone }}</a></div>
    </div>
    <hr>
    <div class="row">
      <div class="col">
        <p><strong>Комментарий</strong></p>
        @if($item->comment)
            <div class="alert warning">{!! nl2br($item->comment) !!}</div>
        @else
            &mdash;
        @endif
      </div>      
    </div>
    
    <hr>
    <div class="row">
     <div class="col"> <small>Добавлен {{ Carbon\Carbon::parse($item->created_at)->formatLocalized('%d.%m.%Y %H:%M') }}<br>Изменен {{ Carbon\Carbon::parse($item->updated_at)->formatLocalized('%d.%m.%Y %H:%M') }}</small></div>
    </div>    
    <hr>
 
    <div class="text-center">
        <!--a class="btn" href="/users/edit/{{ $item->id }}" title="Редактировать" onclick="return cms.ajaction(this);"> <i class="ico left fa fa-pencil"></i>Редактировать</a>
        <a class="btn" onclick="cms.modal.confirm('Удалить пользователя безвозвратно?', function(){document.getElementById('remove-form-{{ $item->id }}').submit();}); return false;" href="#" title="Удалить"><i class="ico left fa fa-trash"></i>Удалить</a>
        <form id="remove-form-{{ $item->id }}" action="/users/remove/{{ $item->id }}" method="POST" style="display: none;" onsubmit="">{{ csrf_field() }}</form-->
        
        <button class="btn" type="button" onclick="return cms.modal.close()">
             Закрыть<i class="ico right fa fa-times text-muted"></i>
        </button>
    </div>
</div>

