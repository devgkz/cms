@extends('admin.layout')

@section('title', 'Редактирование материала'.($page ? ': '.$page->title : ''))

@section('header')
<div class="page-header-main">
    <div class="page-header-right">
        <a class="btn" href="{{ config('cms.admin_uri') }}/pages/add/{{ $id ?: 0 }}" onclick="return cms.ajaction(this);"><i class="ico left fa-plus"></i>Добавить</a>
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
        <a class="tabs__link" href="{{ config('cms.admin_uri') }}/pages/index/{{$id}}">Список страниц</a></li>
    <li class="tabs__item">
        <a class="tabs__link active" href="{{ config('cms.admin_uri') }}/pages/edit/{{$id}}">Содержимое</a></li>    
</ul>

@include('admin.partials.alerts')

<div class="row">
<div class="col-md-8">
    <form class="form" role="form" method="POST" action="{{ config('cms.admin_uri') }}/pages/update/{{ $page->id }}" onsubmit="return cms.ajaxSubmitModal(this)">
        @csrf
        
        <div class="form-group row">
          <div class="col w-150"><label class="form-label -required">Заголовок</label></div>
          <div class="col">
            <input class="input{{ $errors->has('title')?' is-invalid':'' }}" name="title" type="text" value="{{ old('title')?:$page->title }}" autofocus>
          </div>
        </div>
        
        <div class="form-group row">
          <div class="col w-150"><label class="form-label -required">URI</label></div>
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
          <div class="col">
            <div class="form-text">{{ Form::checkbox('in_menu', 1, (bool) $page->in_menu)}} Включать в меню</div>
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
<div class="col-md-4">
    <div class="card">
        <div class="card-block">
            Media placeholder
        </div>
    </div>    
</div>  
</div>

<!-- TinyMCE -->
<script type="text/javascript" src="/admin/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
  selector: "textarea#red_content",
  //theme: "modern",
  language: "ru",
  plugins: [
         //"autoresize pagebreak searchreplace template anchor wordcount",
         "anchor link image lists charmap hr pagebreak",
         "visualblocks code fullscreen media nonbreaking",
         "table contextmenu paste textcolor colorpicker"
   ],
   // formatselect
  toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist blockquote | outdent indent | link image | forecolor backcolor",
  //menubar : false,
  menu: {
        //file: {title: 'File', items: 'newdocument'},
        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
        insert: {title: 'Insert', items: 'link image media anchor | charmap | pagebreak hr nonbreaking'}, 
        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat'}, // | formats
        table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
        view: {title: 'View', items: 'code fullscreen | visualaid visualblocks'},
        //tools: {title: 'Tools', items: 'spellchecker code'}
    },

  content_css : '/css/design.css',
  image_advtab: true,
  relative_urls : false,
  file_browser_callback : elFinderBrowser,
  /* 
  // для собственного ФМ
  file_picker_callback: function (callback, value, meta) {
      tinymce.activeEditor.windowManager.open({
          //file: '/testpage.php',
          file: 'js/elfinder/elfinder.html',
          title: 'Выбор файла',
          width: 940,  
          height: 450,
          resizable: 'yes'
      }, {
          oninsert: function (url, alt, title) {
              url = file.url;
              // Provide file and text for the link dialog
              if (meta.filetype == 'file') {
                  callback(url, {text: alt, title: title});
              }
              // Provide image and alt text for the image dialog
              if (meta.filetype == 'image') {
                  callback(url, {alt: alt});
              }
              // Provide alternative source and posted for the media dialog
              if (meta.filetype == 'media') {
                  callback(url);
              }
          }
      });
      return false;
  }, */


  
  //apply_source_formatting : true,
  //inline_styles : true,
  //preformatted : true,
  remove_linebreaks : false,
  // accessibility_warnings:false,
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