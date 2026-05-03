@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование блога':'Создание блога')

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
            Создание статьи
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{route('admin_blog_store')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if(isset($oModel))
                    <input type="hidden" name="id" value="{{ $oModel->id }}">
                @endif

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Название статьи</label>
                        <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ isset($oModel)?$oModel->name:'' }}">
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">URL <small>(оставьте пустым для автоматической генерации)</small></label>
                        <input type="text" name="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" value="{{ isset($oModel)?$oModel->slug:'' }}">
                        <div class="invalid-feedback">
                            {{ $errors->first('slug') }}
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Категории</label>
                        <select name="categories[]" class="custom-select select2{{ $errors->has('categories') ? ' is-invalid' : '' }}" data-placeholder="Выберите категории" multiple>
                            @foreach($oCategories as $oCategory)
                                <option value="{{ $oCategory->id }}" {{ in_array($oCategory->id,$aExistCatId) ? 'selected':(in_array($oCategory->id,old('categories',[]))?'selected':'') }}>{{ $oCategory->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback">
							<strong>{{ $errors->first('categories') }}</strong>
						</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Очередность</label>
                        <input type="text" name="order" class="form-control" value="{{ isset($oModel)?$oModel->order:(isset($iBlogCount)?$iBlogCount:old('order')) }}">
                    </div>
                </div>
                <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                    <label class="form-label">Текст статьи</label>
                    <textarea name="text" id="article" class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}">{{ isset($oModel)?$oModel->text:old('text') }}</textarea>
                    <span class="invalid-feedback">
						<strong>{{ $errors->first('text') }}</strong>
					</span>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 crop-group">
                        <label class="form-label">Превью новости</label>
                        <div class="crop-result news-result">
                            <div class="crop-result__image">
                                <img src="{{ isset($oModel) && isset($oModel->main_image)?$oModel->main_image->path:'/admin/img/default.jpg' }}">
                            </div>
                            <div class="crop-result__change">
                                <a href="#" class="btn btn-outline-info">Изменить превью</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-cropimage">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Изменение превью
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            </div>
                            <div class="modal-body">

                                <div class="crop-box">
                                    <div class="crop-box__result hidden" >
                                        <img src="{{ isset($oModel) && isset($oModel->main_image)?$oModel->main_image->path:'/admin/img/default.jpg' }}" alt=""/>
                                    </div>
                                    <div class="crop-box__input">
                                        <input type="hidden" name="crop">

                                        <label title="Upload image file" for="inputImage" class="btn btn-secondary">
                                            <input type="file" accept="image/*" name="preview" id="inputImage" class="hidden">
                                            <i class="sidenav-icon ion ion-md-download"></i>
                                            Загрузить изображение
                                        </label>

                                        <div class="crop-box__tools">
                                            <a href="#" id="cropZoomOut" class="btn btn-outline-secondary" title="Уменьшить изображение">
                                                <i class="ion ion-ios-remove-circle-outline"></i>
                                            </a>

                                            <a href="#" id="cropZoomIn" class="btn btn-outline-secondary" title="Увеличить изображение">
                                                <i class="ion ion-ios-add-circle-outline"></i>
                                            </a>

                                            <a href="#" id="cropRotateLeft" class="btn btn-outline-secondary" title="Повернуть влево на 45°">
                                                <i class="ion ion-md-refresh"></i>
                                            </a>

                                            <a href="#" id="cropRotateRight" class="btn btn-outline-secondary" title="Повернуть вправо на 45°">
                                                <i class="ion ion-md-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-success crop-save" data-dismiss="modal">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>
    </div>

@endsection

@section('css')
    <link href="{{ asset('/admin/libs/cropper/cropper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/admin/libs/sweetalert2/sweetalert2.css') }}">

@endsection

@section('scripts')
    <script src="{{ asset('/admin/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/admin/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('/admin/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('/admin/js/cropimage.js') }}"></script>



    <script>
        tinymce.init({...tiny_mce_conf});
    </script>
@endsection
