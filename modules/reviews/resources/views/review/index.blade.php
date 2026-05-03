@extends('layouts.main')
@section('content')
    @foreach($oModels as $oModel)
        @if(empty($oModel->user_id))
            <img src="{{ ($oModel->image)?$oModel->image->path:'' }}"><br>
            {{ $oModel->name }}<br>
        @else
            <img src="{{ ($oModel->author->main_image)?$oModel->author->main_image->path:'' }}"><br>
            {{ $oModel->author->name }} {{ $oModel->author->surname }}<br>
        @endif
        {!! $oModel->text !!}
        <hr>
    @endforeach
    @if(auth()->check())
        <div>
            <h2>Auth form</h2>
            <form method="post" action="{{ route('review_store') }}">
                {{ csrf_field() }}
                Текст отзыва:
                <textarea name="text"></textarea><br>
                <input type="submit" value="Отправить">
            </form>
        </div>
    @else
        <div>
            <h2>Unauth form</h2>
            <form method="post" action="{{ route('review_store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                ФИО: <input type="text" name="name"><br>
                Аватар: <input type="file" name="preview"><br>
                Текст отзыва:
                <textarea name="text"></textarea><br>
                <input type="submit" value="Отправить">
            </form>
        </div>
    @endif

@endsection