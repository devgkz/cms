@extends('admin.layout')

@section('title', 'О программе')

@section('header')
<div class="page-header-main"><i class="ico left fa-info-circle text-info"></i>О программе</div>
@endsection

@section('content')

    @parsedown($text)

@endsection