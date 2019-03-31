@extends('admin.layout')

@section('title', 'Настройки')

@section('header')
<div class="page-header-main">
    <i class="ico left fa-cog text-info"></i>Настройки
</div>
@endsection

@section('content')

<form method="post" id="edit-form" onsubmit="cms.ajaxSubmit(this, formOptions);return false">
    @csrf

    @foreach($settings as $item)
    <div class="form-group row">
        <div class="col-md-4"><label class="form-label"><strong>{{ $item->title }}</strong><br>
            <small>{{ $item->comment }}</small></label></div>
        <div class="col">
            <input class="input" name="data[{{ $item->name }}]" type="text" value="{{ $item->value }}">
        </div>
    </div>
    <hr class="show-md">
    @endforeach
    
    <button class="btn primary" type="submit">Сохранить</button>
</form>

<script type="text/javascript">
formOptions = {
    before:  function () {
        cms.modal.wait(0);
    },
    success: function (data) {
        cms.modal.waitClose();
        cms.notice.show("Настройки сохранены", "success", 1000);
    },
    error: function (data) {
        cms.modal.waitClose();
        cms.modal.alert('Ошибка связи. Проверьте интернет<br> и попробуйте еще раз', cms.modal.closeAll);
    },
}
</script>
@endsection