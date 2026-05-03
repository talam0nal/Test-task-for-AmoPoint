@extends('layouts.main')
@section('content')
    @foreach($oModel->images as $oImage)
        <img src="{{ $oImage->path }}"><br>
    @endforeach
    {{ $oModel->name }}<br>
    {!! $oModel->content !!}<br>
    @foreach($oModel->categories as $oCategory)
        {{ $oCategory->name }},
    @endforeach
@endsection