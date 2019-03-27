@extends('admin.layout')

@section('title', 'Настройки')

@section('header')
<div class="page-header-main">
    <i class="ico left fa-cog"></i>Настройки
</div>
@endsection

@section('content')

<form method="post" id="edit-form" onsubmit="cms.ajaxSubmitModal(this);return false">
{{ csrf_field() }}
<table class="table grid" style="width:auto">
<tbody>
@foreach($settings as $item)
    <tr>
        <td><strong>{{ $item->title }}</strong><br>
            <small>{{ $item->comment }}</small>
        </td>
        <td style="text-align:left">
            <input class="input" name="data[{{ $item->name }}]" type="text" value="{{ $item->value }}" style="min-width: 400px;width:100%">
        </td>
    </tr>
@endforeach
</tbody>
</table>
<button class="btn primary" type="submit"><i class="ico left fa-save"></i>Сохранить</button>
</form>
@endsection