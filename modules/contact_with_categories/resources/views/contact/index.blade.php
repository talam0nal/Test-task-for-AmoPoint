@extends('layouts.main')
@section('content')
    @foreach($oModels as $oModel)
        <a href="{{ route('contact_show',['oModel'=>$oModel]) }}">{{ $oModel->name }}</a><br>
        {{ $oModel->category->name }}
        <hr>
    @endforeach
@endsection