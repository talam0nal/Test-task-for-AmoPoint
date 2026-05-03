@extends('admin.layout')

@section('title', isset($oModel)?'Редактирование пользователя':'Создание пользователя')

@section('content')

    @if (count($errors) > 0)
        <!-- Список ошибок формы -->
        <div class="alert alert-danger">
            <strong>Упс! Что-то пошло не так!</strong>

            <br><br>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <h6 class="card-header">
            Редактирование пользователя
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{route('admin_user_store')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if(isset($oModel))
                    <input type="hidden" name="id" value="{{ $oModel->id }}">
                @endif
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ isset($oModel)?$oModel->name:'' }}">
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Фамилия <small>(не обязательно)</small></label>
                        <input type="text" name="surname" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" value="{{ isset($oModel)?$oModel->surname:'' }}">
                        <div class="invalid-feedback">
                            {{ $errors->first('surname') }}
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ isset($oModel)?$oModel->email:'' }}">
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Тип пользователя</label>
                        <select name="is_admin" data-placeholder="Выберите тип" class="custom-select">
                            <option value="0" {{ (isset($oModel) && $oModel->is_admin==0)?'selected':'' }}>Пользователь</option>
                            <option value="1" {{ (isset($oModel) && $oModel->is_admin==1)?'selected':'' }}>Администратор</option>
                            {{--<option value="2" {{ (isset($oModel) && $oModel->is_admin==2)?'selected':'' }}>Контент менеджер</option>--}}
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">

                        <div class="cropper-example-container">
                            <img id="cropper-example-image" data-crop_x="1" data-crop_y="1" src="{{ isset($oModel) && isset($oModel->image)?$oModel->image->path:'/admin/img/default.jpg' }}" alt="Picture">
                        </div>

                    </div>
                    <div class="col-md-3">

                        <!-- Preview -->
                        <div class="mb-3 clearfix">
                            <h6>Preview 400x400</h6>
                            <div class="cropper-example-preview lg"></div>
                            <div class="cropper-example-preview md"></div>
                            <div class="cropper-example-preview sm"></div>
                            <div class="cropper-example-preview xs"></div>
                        </div>

                        <input type="hidden" name="crop">
                        <label class="btn btn-primary btn-upload" data-toggle="cropper-example-tooltip" title="Upload image file">
                            <input type="file" class="sr-only" id="cropper-example-inputImage" name="preview" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                            <span class="ion ion-md-cloud-upload" style="font-family: inherit;"> Загрузить изображение</span>
                        </label>

                    </div>
                </div>
                <div class="row">
                    <div class="cropper-example-buttons col-md-9">

                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;zoom&quot;,&nbsp;0.1)">
                                <span class="ion ion-md-add"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;zoom&quot;,&nbsp;-0.1)">
                                <span class="ion ion-md-remove"></span>
                            </button>
                        </div>

                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;move&quot;,&nbsp;-10,&nbsp;0)">
                                <span class="ion ion-md-arrow-back"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;move&quot;,&nbsp;10,&nbsp;0)">
                                <span class="ion ion-md-arrow-forward"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;move&quot;,&nbsp;0,&nbsp;-10)">
                                <span class="ion ion-md-arrow-up"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;move&quot;,&nbsp;0,&nbsp;10)">
                                <span class="ion ion-md-arrow-down"></span>
                            </button>
                        </div>

                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;rotate&quot;,&nbsp;-45)">
                                <span class="ion ion-md-refresh"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;rotate&quot;,&nbsp;45)">
                                <span class="ion ion-md-refresh scaleX--1"></span>
                            </button>
                        </div>

                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;scaleX&quot;,&nbsp;-1)">
                                <span class="ion ion-md-swap"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;scaleY&quot;,&nbsp;-1)">
                                <span class="ion ion-md-swap rotate--90"></span>
                            </button>
                        </div>

                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary" data-method="reset" data-toggle="cropper-example-tooltip" title="$().cropper(&quot;reset&quot;)">
                                <span class="ion ion-md-sync"></span>
                            </button>
                        </div>

                    </div>
                </div>



                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>

@endsection

@section('css')
    <link href="{{ asset('/admin/libs/cropper/cropper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/admin/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('/admin/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('/admin/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('/admin/js/cropimage.js') }}"></script>
@endsection

