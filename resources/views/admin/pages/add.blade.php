<h1 class="mb-3">Добавить страницу</h1>
<hr>
@include('admin.partials.alerts')


<div style="">
<form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/store/{{$parentId}}" onsubmit="return cms.ajaxSubmitModal(this)">
    @csrf
    
    <div class="form-group row">
      <div class="col-md-5"><label class="form-label -required">Заголовок</label></div>
      <div class="col">
        <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title') }}" autofocus>
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col-md-5"><label class="form-label -required">URI</label></div>
      <div class="col">
        <input class="input{{ $errors->has('slug')?' is-invalid':'' }}" name="slug" type="text" value="{{ old('slug') }}">
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col-md-5"><label class="form-label nowrap">Шаблон (поведение)</label></div>
      <div class="col">
        {{ Form::select('template', App\Models\Page::getTemplates(), old('template'), ['class'=>'select']) }}
      </div>
    </div>
    
    <div class="form-group row">
      <div class="col-md-5"><label class="form-label nowrap">Макет страницы</label></div>
      <div class="col">
        {{ Form::select('layout', App\Models\Page::getLayouts(), old('layout'), ['class'=>'select']) }}
      </div>
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

