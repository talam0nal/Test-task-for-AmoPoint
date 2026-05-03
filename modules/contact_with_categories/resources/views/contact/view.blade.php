@extends('layouts.main')
@section('content')
    {{ $oModel->name }}<br>
    {!! $oModel->value !!}<br>
    {{ $oModel->category->name }}
@endsection