@extends('layouts.main')
@section('content')
    @foreach($oModels as $oModel)

        <a href="{{ route('static_page_show',['oModel'=>$oModel]) }}">{{ $oModel->name }}</a><br>
        <hr>
    @endforeach
@endsection