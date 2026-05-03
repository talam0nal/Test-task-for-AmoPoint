@extends('admin.layout')

@section('page_name', 'Удалить модуль-конструктор?')

@section('content')
    <div class="card mb-4">
        <h6 class="card-header">
            Вы действительно хотите удалить модуль-конструктор?
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{ route('admin_construct_destroy_module') }}">
                {{ csrf_field() }}
                {{ method_field("DELETE") }}
                <p>Все файлы и записи об этому модуле будут удалены из проекта</p>
                <div class="form-group">
                    <div class="col-lg-12 text-right">
                        <input class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit" value="Да, удалить модуль">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection