@extends('layouts.main')
@section('content')
    <img src="{{ ($oModel->image)?$oModel->image->path:'' }}"><br>
    {{ $oModel->name }}<br>
    {!! $oModel->text !!}<br>
    @foreach($oModel->categories as $oCategory)
        {{ $oCategory->name }},
    @endforeach
@endsection