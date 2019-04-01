<h1 class="mb-3">Добавить медиа</h1>
<hr>
@include('admin.partials.alerts')


<div style="">
<form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/media/store/{{$pageId}}" onsubmit="return cms.ajaxSubmitModal(this)">
    @csrf

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label nowrap -required">Тип</label></div>
        <div class="col">
            {{ Form::select('type_id', App\Models\Media::getTypes(), old('type_id'), ['class'=>'select']) }}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label">Файл</label></div>
        <div class="col">
            <span title="прикрепить файлы" class="btn btn-upload {{ $errors->has('file')?' is-invalid':'' }}">
                <i class="ico fa-paperclip left"></i><span>Выбрать...</span>
                <input type="file" multiple="multiple" name="file" id="file" accept="image/gif, image/png">
            </span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label">Заголовок</label></div>
        <div class="col">
            <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title') }}" autofocus>
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

