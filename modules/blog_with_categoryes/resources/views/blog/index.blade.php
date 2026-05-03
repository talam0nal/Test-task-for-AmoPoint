@extends('layouts.main')
@section('content')
    @foreach($oModels as $oModel)
        <img src="{{ ($oModel->image)?$oModel->image->path:'' }}"><br>
        <a href="{{ route('blog_show',['oModel'=>$oModel]) }}">{{ $oModel->name }}</a><br>
        @foreach($oModel->categories as $oCategory)
            {{ $oCategory->name }},
        @endforeach
        <hr>
    @endforeach
@endsection