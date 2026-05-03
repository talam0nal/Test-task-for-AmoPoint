@extends("admin.layout")

@section("page_name", isset($oModel)?"Редактирование записи":"Создание записи")

@section("content")
    @if(count($errors) > 0)
        <!-- Список ошибок формы -->
        <div class="alert alert-danger">
            <strong>Упс! Что-то пошло не так!</strong>

            <br><br>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
            </ul>
        </div>
     @endif

    <div class="card mb-4">
        <h6 class="card-header">
            Создание записи
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{ route("admin_dialog_store") }}" enctype="multipart/form-data" >
                {{ csrf_field() }}
                @if(isset($oModel))
                    <input type="hidden" name="id" value="{{ $oModel->id }}">
                @endif
                <div class="form-group">
                    <label class="form-label">Last_Message</label>
                            <input type="text" name="last_message" class="form-control{{ $errors->has("last_message") ? " is-invalid" : "" }}" value="{{ old("last_message",false)?old("last_message"):(isset($oModel)?$oModel->last_message:'') }}">
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("last_message") }}</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Sender</label>
                    <select name="sender_id" data-placeholder="Выберите родителя" class="select2 custom-select{{ $errors->has("sender_id") ? " has-error" : "" }}">
                        @foreach($oUsers as $oUser)
                            <option value="{{ $oUser->id }}" {{ old("sender_id",false)?($oUser->id==old("sender_id")?"selected":""):((isset($oModel) && $oModel->sender_id==$oUser->id)?"selected":'')  }}>{{ $oUser->id }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("sender_id") }}</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Reader</label>
                    <select name="reader_id" data-placeholder="Выберите родителя" class="select2 custom-select{{ $errors->has("reader_id") ? " has-error" : "" }}">
                        @foreach($oUsers as $oUser)
                            <option value="{{ $oUser->id }}" {{ old("reader_id",false)?($oUser->id==old("reader_id")?"selected":""):((isset($oModel) && $oModel->reader_id==$oUser->id)?"selected":'')  }}>{{ $oUser->id }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("reader_id") }}</strong>
                    </span>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" >Опубликовать</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('css')
@endsection

@section('scripts')
    <script src="{{ asset('/admin/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({...tiny_mce_conf});

    </script>
@endsection
