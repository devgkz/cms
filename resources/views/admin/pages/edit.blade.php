@extends('admin.layout')

@section('title', 'Редактирование материала'.($page ? ': '.$page->title : ''))

@section('header')
<div class="page-header-main">
    <div class="page-header-right">
        <a class="btn" href="{{ config('cms.admin_uri') }}/pages/add/{{ $page->parent_id ?: 0 }}" onclick="return cms.ajaction(this);"><i class="ico left fa-plus"></i>Добавить в раздел</a>
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
    <li class="tabs__item">
        <a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages/index/{{$id}}">Страницы</a></li>
    <li class="tabs__item">
        <a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages/edit/{{$id}}">Содержимое</a></li>
</ul>

@include('admin.partials.alerts')

<div class="row">
<div class="col-md-8">
    <div class="mb-2">
    <form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/update/{{ $page->id }}" onsubmit="return cms.ajaxSubmitModal(this)">
        @csrf

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label -required">Заголовок</label></div>
          <div class="col">
            <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title')?:$page->title }}" autofocus>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label -required">URI</label></div>
          <div class="col">
            <input class="input{{ $errors->has('slug')?' is-invalid':'' }}" name="slug" type="text" value="{{ old('slug')?:$page->slug }}">
          </div>
        </div>

        <div class="form-group top-label">
          <label class="form-label">Контент</label>
          <textarea class="input" id="red_content" name="content" rows="20">{{ old('content')?:$page->content }}</textarea>
        </div>

        <div class="form-group top-label">
          <label class="form-label">Интротекст</label>
          <textarea class="input" name="introtext" rows="3">{{ old('introtext')?:$page->introtext }}</textarea>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Шаблон (поведение)</label></div>
          <div class="col">
            {{ Form::select('template', App\Models\Page::getTemplates(), old('template')?:$page->template, ['class'=>'select']) }}
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Макет страницы</label></div>
          <div class="col">
            {{ Form::select('layout', App\Models\Page::getLayouts(), old('layout')?:$page->layout, ['class'=>'select']) }}
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Включать в меню</label></div>
          <div class="col">
            {{ Form::hidden('in_menu', '0') }}
            {{ Form::checkbox('in_menu', 1, (bool) $page->in_menu, ['class'=>'switchbox'])}}
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Закрепить вверху</label></div>
          <div class="col">
            {{ Form::hidden('is_pin', '0') }}
            {{ Form::checkbox('is_pin', 1, (bool) $page->is_pin, ['class'=>'switchbox'])}}
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Меню раздела</label></div>
          <div class="col">
            {{ Form::select('section_menu', App\Models\Page::getSectionMenuTypes(), old('section_menu')?:$page->section_menu, ['class'=>'select']) }}
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Сортировка вложенных страниц</label></div>
          <div class="col">
            <input class="input{{ $errors->has('order_childs_by')?' is-invalid':'' }}" name="order_childs_by" type="text" value="{{ old('order_childs_by')?:$page->order_childs_by }}">
          </div>
        </div>


        <hr>
        <div class="form-group row">
          <div class="col-md-4"><label class="form-label">Статус публикации</label></div>
          <div class="col">
            {{ Form::select('status', App\Models\PageStatusList::all(), old('status')?:$page->status, ['class'=>'select']) }}
          </div>
        </div>

        <hr>
        <div class="">
            <button type="submit" class="btn primary">
                <i class="ico left fa fa-check"></i> Сохранить
            </button>

            <button class="btn" type="button" onclick="return cms.modal.closeAll()">
                 Отмена<i class="ico right fa fa-times text-muted"></i>
            </button>
        </div>
    </form>
    </div>
</div>
<div class="col-md-4">
    <div style="padding-left:.7rem; border-left:1px solid #eee">
        <div class="row">
            <div class="col-md-8">
                <h4 class="text-bold">Прикрепленное медиа-содержимое</h4>
            </div>             
            <div class="col text-right">
                <a class="btn" href="{{ config('cms.admin_uri') }}/pages/media/add/{{ $page->id }}" onclick="return cms.ajaction(this);"><i class="ico left fa-plus"></i>Добавить</a>
            </div>
        </div>

       <table class="table bordered grid full" style="width:100%">
            <thead>
                <tr>
                    <th>ID<i class="ico right fa-arrows-alt-v"></i></th>
                    <th style="width:100px" class="text-muted">*</th>
                    <th>Тип</th>
                    <th class="text-right text-muted">**</th>
                </tr>
            </thead>
            <tbody>
                @forelse($media as $m)
                <tr id="{{ $m->id }}">
                    <td class="dragHandle"><small>{{ $m->id }}</small></td>
                    <td>
                        <small>{{ $m->title }}</small>
                        @if($m->type_id == 0)
                            <br><a href="/files/{{ $m->filename }}" target="_blank"><image src="/files/resized/40x40/{{ $m->filename }}" /></a>
                        @endif
                    </td>
                    <td><small>{{ App\Models\Media::getTypes()[$m->type_id] }}</small></td>
                    <td class="text-right" style="white-space:nowrap">
                          <a class="text-info" href="{{ config('cms.admin_uri') }}/pages/media/edit/{{ $m->id }}" title="Редактировать" onclick="return cms.ajaction(this);"> <i class="fa fa-pen"></i></a>
                          <span class="p-1"></span>
                          <a class="text-danger" onclick="cms.modal.confirm('Удалить медиа?', function(){document.getElementById('remove-form-{{ $m->id }}').submit();}); return false;" href="#" title="Удалить"><i class="fa fa-trash"></i></a></div>
                          <form id="remove-form-{{ $m->id }}" action="{{ config('cms.admin_uri') }}/pages/media/remove/{{ $m->id }}" method="POST" style="display: none;" onsubmit="">{{ csrf_field() }}</form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-xs-center">Нет медиа</td></tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
</div>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});
$(".grid").tableDnD({
    onDragClass: "myDragClass",
    dragHandle: "dragHandle",
    onDrop: function(table, row) {
        var rows = table.tBodies[0].rows;
        var str = "";
        for (var i = 0; i < rows.length; i++) {
          str += rows[i].id + ";";
        }
        $.post("{{ config('cms.admin_uri') }}/main/save_order", {data: str, table: "media"})
        .done(function(data) {
          cms.notice.show("Сортировка сохранена", "success", 1000);
        })
        .fail(function(error) {
          console.log(error);
          if (error.status != 0) {
            cms.modal.alert(error.status+' '+error.statusText, cms.modal.close);
          } else {
            cms.modal.alert('Internet connection error', cms.modal.closeAll);
          }      
        });
    }
});
</script>

<script type="text/javascript" src="/admin/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
  selector: "textarea#red_content",
  //theme: "modern",
  language: "ru",
  plugins: [
         //"autoresize pagebreak searchreplace template anchor wordcount",
         "anchor link image lists charmap hr",
         "visualblocks code fullscreen media nonbreaking", //
         "table"
   ],
   // formatselect
  toolbar: "styleselect | bold italic strikethrough| subscript superscript | forecolor backcolor | removeformat | alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist blockquote | link image media charmap nonbreaking hr table | visualblocks code fullscreen",
  menubar : false,

  //content_css : '/css/design.css',
  image_advtab: true,
  relative_urls : false,
  file_browser_callback : elFinderBrowser,
  remove_linebreaks : false,
  branding: false
});

function elFinderBrowser (field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: '/admin/elfinder/elfinder.html',// use an absolute path!
    title: 'Менеджер файлов',
    width: 940,
    height: 450,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
}
</script>
@endsection
