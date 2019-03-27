<h1 class="mb-3">Добавить пользователя</h1>
<hr>
@include('admin.partials.alerts')


<div style="max-width:400px">
<form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/users/store" onsubmit="return cms.ajaxSubmitModal(this)">
    @csrf
    
    <div class="form-group row">
      <div class="col w-150"><label class="form-label -required">ФИО</label></div>
      <div class="col">
        <input class="input{{ $errors->has('name')?' is-invalid':'' }}" name="name" type="text" value="{{ old('name') }}" autofocus>        
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col w-150"><label class="form-label -required">Группа</label></div>
      <div class="col">
        {{ Form::select('role', App\Models\UserRoleList::all(), old('role'), ['class'=>'select']) }}
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col w-150"><label class="form-label -required">E-mail</label></div>
      <div class="col">
        <input class="input{{ $errors->has('email')?' is-invalid':'' }}" name="email" type="email" value="{{ old('email') }}">        
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col w-150"><label class="form-label -required">Пароль</label></div>
      <div class="col">
        <input class="input{{ $errors->has('password')?' is-invalid':'' }}" name="password" type="text" value="{{ old('password')?:$password }}">
        <div class="form-text"><input type="checkbox" value="1" name="send_password" checked> Отправить пароль на указанную почту</div>
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col w-150"><label class="form-label -required">Телефон</label></div>
      <div class="col">
        <input class="input{{ $errors->has('phone')?' is-invalid':'' }}" name="phone" type="text" value="{{ old('phone') }}">
      </div>
    </div>
    
    <div class="form-group top-label">
      <label class="form-label">Комментарий<br>
        <em><small>(виден только администраторам)</small></em></label>      
      <textarea class="input" name="comment" rows="3">{{ old('comment') }}</textarea>        
    </div>
    
    <div class="text-center">
        <button type="submit" class="btn primary">
            <i class="ico left fa fa-check"></i> Сохранить
        </button>
        
        <button class="btn" type="button" onclick="return cms.modal.closeAll()">
             Отмена<i class="ico right fa fa-times text-muted"></i>
        </button>
    </div>
</form>
</div>

