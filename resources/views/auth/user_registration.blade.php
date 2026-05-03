@extends('resources.views.layouts.main')
@section('title','Registration')
@section('content')
    <section id="content">
        <div class="inner">
            <section id="login">
                <div class="form">
                    <h1>Регистрация</h1>
                    <form autocomplete="off" method="post" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            <input type="text" class="form-control" name="name" placeholder="Имя/Логин" autocomplete="off" value="{{ old('name') }}">
                            {{--<input type="text" name="name" placeholder="Ваше Email или телефон" autocomplete="off">--}}
                        </div>
                        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('surname') }}</strong>
                            </span>
                            <input type="text" class="form-control" name="surname" placeholder="Фамилия" autocomplete="off" value="{{ old('surname') }}">
                            {{--<input type="text" name="name" placeholder="Ваше Email или телефон" autocomplete="off">--}}
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" value="{{ old('email') }}">
                            {{--<input type="text" name="name" placeholder="Ваше Email или телефон" autocomplete="off">--}}
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            <input type="password" class="form-control" name="password" placeholder="Пароль" autocomplete="off">
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Повторите пароль" autocomplete="off">
                        </div>
                        {{--<a href="#" class="btn btn-yellow">Зарегистрироватся</a>--}}
                        <input type="submit" class="btn btn-yellow" value="Зарегистрироваться">
                        <div class="center"><a href="{{ route('user_login') }}">Войти</a></div>
                    </form>
                </div>
            </section>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection