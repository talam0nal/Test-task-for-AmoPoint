@extends('resources.views.layouts.main')
@section('title','Login')
@section('content')
    <section id="content">
        <div class="inner">
            <section id="login">
                <div class="form">
                    <h1>Вход</h1>
                    <form autocomplete="off" method="post" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off">
                            {{--<input type="text" name="name" placeholder="Ваше имя" autocomplete="off">--}}
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            <input type="password" class="form-control" name="password" placeholder="Пароль" autocomplete="off">
                        </div>
                        <div class="restore"><a href="{{ route('password.request') }}">Забыли пароль?</a></div>
                        {{--<a href="#" class="btn btn-yellow">Войти</a>--}}
                        <input type="submit" class="btn btn-yellow" value="Войти">
                        <div class="center"><a href="{{ route('user_registration') }}">Зарегистрироваться</a></div>
                    </form>
                </div>
            </section>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection