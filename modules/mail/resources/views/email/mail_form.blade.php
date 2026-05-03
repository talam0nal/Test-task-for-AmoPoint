@extends('layouts.main')
@section('content')
    <div>
        {{ print_r($errors) }}
        <form method="post" action="{{ route('send_mail') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h2>Задайте вопрос</h2>
            <div>
                <input type="text" name="sender_name" value="{{ old('sender_name','') }}" placeholder="Ваше имя">
            </div>
            <div>
                <input type="text" name="sender_email" value="{{ old('sender_email','') }}" placeholder="Ваш Email">
            </div>
            <div>
                <textarea name="sender_message" placeholder="Ваше сообщение">{{ old('sender_message','') }}</textarea>
            </div>
            <div>
                <input type="file" name="sender_file">
            </div>
            <div>
                <button type="submit">Отправить</button>
            </div>
        </form>
    </div>
@endsection