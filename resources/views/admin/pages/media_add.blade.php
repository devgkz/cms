<h1 class="mb-3">Добавить медиа</h1>
<hr>
@include('admin.partials.alerts')

<div style="">
<form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/media/store/{{$pageId}}" onsubmit="return cms.ajaxSubmit(this, mediaFormOptions)">
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
                <input type="file" multiple="multiple" name="file[]" id="file" accept="image/gif, image/png">
            </span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5"><label class="form-label">Заголовок</label></div>
        <div class="col">
            <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title') }}" autofocus>
        </div>
    </div>
    
    <div class="form-group top-label">
        <label class="form-label">Контент</label>
        <textarea class="input" name="content" rows="3">{{ old('content') }}</textarea>
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
        cms.modal.waitClose();
        cms.notice.show("Медиа добавлено", "success", 1000);
        window.location.reload();
    }
};

// btn-upload
$(function() {
    var fileInput = document.getElementById('file');
    defaultLabel = $(fileInput).parent('.btn-upload').find('span').text();

    fileInput.addEventListener('change', function(e) {

    {{--  if (fileInput.value.substring(fileInput.value.lastIndexOf('.') + 1, fileInput.value.length).toLowerCase() != 'png') {
        alert('Only png files are accepted!');
        fileInput.value = null;
        $(fileInput).parent('.btn-upload').find('span').html(defaultLabel);
    }  --}}

    fileNames = [];

    if (e.target.files.length) {
        for (i=0; i < e.target.files.length; i++) {
        fileNames.push(e.target.files[i].name);
        };
    }

    if (fileInput.files.length) {
        $(fileInput).parent('.btn-upload').addClass('selected');
        $(fileInput).parent('.btn-upload').find('span').html('Выбрано (' + fileNames.length + ') ');
        $(fileInput).parent('.btn-upload').attr('title', fileNames.join(" \n").trim());
    } else {
        $(fileInput).parent('.btn-upload').removeClass('selected');
        $(fileInput).parent('.btn-upload').find('span').html(defaultLabel);
    }
    }, false);

});
</script>
    