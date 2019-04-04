<h1 class="mb-3">Добавить медиа</h1>
<hr>
@include('admin.partials.alerts')

<div style="">
<form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/media/update/{{$item->id}}" onsubmit="return cms.ajaxSubmit(this, mediaFormOptions)">
    @csrf

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label nowrap -required">Тип</label></div>
        <div class="col">
            {{ Form::select('type_id', App\Models\Media::getTypes(), old('type_id')?:$item->type_id, ['class'=>'select']) }}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label">Файл</label></div>
        <div class="col">
            @if($item->type_id == 0)
                <a href="/files/{{ $item->filename }}" target="_blank"><image src="/files/resized/40x40/{{ $item->filename }}" /></a>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label">Заголовок</label></div>
        <div class="col">
            <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title')?:$item->title }}" autofocus>
        </div>
    </div>
    
    <div class="form-group top-label">
        <label class="form-label">Контент</label>
        <textarea class="input" name="content" rows="3">{{ old('content')?:$item->content }}</textarea>
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

<script type="text/javascript">
mediaFormOptions = {
    success: function (data) {
        console.log(data);
        cms.modal.waitClose();
        if (data.responseText == 'ok') {
            cms.notice.show("Медиа изменено", "success", 1000);
            window.location.reload();
        } else {
            cms.modal.open(data.responseText);
        }
        
    }
};
</script>
    