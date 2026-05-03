@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование кейса':'Создание кейса')

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
            {{ isset($oModel)?'Редактирование кейса':'Создание кейса' }}
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{route('admin_portfolio_store')}}" enctype="multipart/form-data">
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
                        <input type="text" name="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" value="{{ old("slug",false)?old("slug"):(isset($oModel)?$oModel->slug:'') }}">
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
                        <input type="text" name="order" class="form-control" value="{{ isset($oModel)?$oModel->order:(isset($iPortfolioCount)?$iPortfolioCount:old('order')) }}">
                    </div>
                </div>
                <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                    <label class="form-label">Текст статьи</label>
                    <textarea name="text" id="article" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ isset($oModel)?$oModel->content:old('content') }}</textarea>
                    <span class="invalid-feedback">
						<strong>{{ $errors->first('text') }}</strong>
					</span>
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

                <h6 class="mt-4">Изображения</h6>
                <div class="form-group drop-group" data-name="images_id" data-maxwidth="1920" data-folder="portfolio">
                    <div class="flow-error alert alert-danger">
                        Your browser, unfortunately, is not supported by Flow.js. The library requires support for
                        <a href="http://www.w3.org/TR/FileAPI/">the HTML5 File API</a> along with
                        <a href="http://www.w3.org/TR/FileAPI/#normalization-of-params">file slicing</a>.
                    </div>

                    <div class="flow-drop py-5 px-3" ondragenter="$(this).addClass('flow-dragover')" ondragend="$(this).removeClass('flow-dragover')" ondrop="$(this).removeClass('flow-dragover')">
                        <h4>Drop files here to upload or</h4>
                        <button type="button" class="flow-browse btn btn-secondary">Select from your computer</button>
                        <button type="button" class="flow-browse-image btn btn-secondary">Select images</button>
                        <button type="button" class="flow-browse-folder btn btn-secondary">Select folder</button>
                    </div>

                    <div class="flow-progress media d-none mt-4">
                        <div class="mr-3">
                            <button type="button" class="progress-resume-link btn icon-btn btn-primary">
                                <i class="ion ion-md-play"></i>
                            </button>
                            <button type="button" class="progress-pause-link btn icon-btn btn-warning">
                                <i class="ion ion-md-pause"></i>
                            </button>
                            <button type="button" class="progress-cancel-link btn icon-btn btn-danger">
                                <i class="ion ion-md-close"></i>
                            </button>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="progress-container progress">
                                <div class="progress-bar"></div>
                            </div>
                        </div>
                    </div>

                    <ul class="flow-list list-group mt-4">
                        @if(isset($oModel))
                            @foreach($oModel->images as $oImage)
                                @if($oImage->is_main == true)
                                    @continue
                                @endif
                                <li class="flow-file list-group-item flow-file-{{ $oImage->id }}">
                                    <input type="hidden" name="images_id[]" value="{{ $oImage->id }}">
                                    <div class="flow-progress media">
                                        <div class="media-body">
                                            <div><img src="{{ $oImage->path }}" class="mr-2" alt="" height="48px">
                                                <strong class="flow-file-name">{{ $oImage->path }}</strong> - <em class="flow-file-progress">(loaded)</em>
                                            </div>

                                        </div>
                                        <div class="ml-3 align-self-center">  <button type="button" data-file="flow-file-{{ $oImage->id }}" class="delete-file flow-file-cancel btn btn-sm icon-btn btn-outline-danger"><i class="ion ion-md-close"></i></button></div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" >Опубликовать</button>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('css')
    <link href="{{ asset('/admin/libs/cropper/cropper.css') }}" rel="stylesheet">
    <link href="{{ asset('/admin/libs/flow-js/flow.css') }}" rel="stylesheet">

@endsection

@section('scripts')
    <script src="{{ asset('/admin/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/admin/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('/admin/js/cropimage.js') }}"></script>
    <script src="{{ asset('/admin/libs/flow-js/flow.js') }}"></script>

    <script>
        tinymce.init({...tiny_mce_conf});

    </script>
@endsection
