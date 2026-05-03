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
            <form method="post" class="form-horizontal" action="{{ route("admin_message_store") }}" enctype="multipart/form-data" >
                {{ csrf_field() }}
                @if(isset($oModel))
                    <input type="hidden" name="id" value="{{ $oModel->id }}">
                @endif
                <div class="form-group">
                    <label class="form-label">Text</label>
                            <input type="text" name="text" class="form-control{{ $errors->has("text") ? " is-invalid" : "" }}" value="{{ old("text",false)?old("text"):(isset($oModel)?$oModel->text:'') }}">
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("text") }}</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Dialog</label>
                    <select name="dialog_id" data-placeholder="Выберите родителя" class="select2 custom-select{{ $errors->has("dialog_id") ? " has-error" : "" }}">
                        @foreach($oDialogs as $oDialog)
                            <option value="{{ $oDialog->id }}" {{ old("dialog_id",false)?($oDialog->id==old("dialog_id")?"selected":""):((isset($oModel) && $oModel->dialog_id==$oDialog->id)?"selected":'')  }}>{{ $oDialog->id }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("dialog_id") }}</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <select name="author_id" data-placeholder="Выберите родителя" class="select2 custom-select{{ $errors->has("author_id") ? " has-error" : "" }}">
                        @foreach($oUsers as $oUser)
                            <option value="{{ $oUser->id }}" {{ old("author_id",false)?($oUser->id==old("author_id")?"selected":""):((isset($oModel) && $oModel->author_id==$oUser->id)?"selected":'')  }}>{{ $oUser->id }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("author_id") }}</strong>
                    </span>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title  back-change">
                            <h5>File</h5>
                        </div>
                        <div class="ibox-content">
                            <p>
                                Attach file if it's necessary
                            </p>
                            <div class="row">

                                <div class="col-md-6">
                                    @if(!empty($oModel->file_path))
                                        <p> Attached file: <a href="{{ route('download',['oMessage'=>$oModel]) }}">{{ $oModel->file_name }}</a></p>
                                    @endif
                                    <h4>Upload file</h4>
                                    <div class="btn-group">
                                        <label title="Upload file" for="inputFile" class="btn btn-primary">
                                            <input type="file" name="file" id="inputFile" class="hide">
                                            Upload file
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{--<div class="image-crop"  >
                                        <img src="{{ (isset($oModel) && !empty($oModel->image))?$oModel->image->path:"/admin/img/default.jpg" }}">
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
						<span class="help-block">
                            <strong>{{ $errors->first("preview") }}</strong>
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
