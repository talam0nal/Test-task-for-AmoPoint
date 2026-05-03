@extends('layouts.main')

@section("sTitleTag", $oModel->seo_title??$oModel->name." | ".config('app.name'))
@section("sDescTag", $oModel->seo_description??"")
@section("sKeywordsTag", $oModel->seo_keywords??"")

@section('content')
    <h1>{{ $oModel->name }}</h1>
    <div class="content">
        {!! $oModel->text !!}
    </div>
@endsection
