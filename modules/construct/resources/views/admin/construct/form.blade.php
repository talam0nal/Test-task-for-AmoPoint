@@extends("admin.layout")

@@section("page_name", (isset($oModel)?"Редактирование ":"Создание ")."записи")

@@section("content")
    @@if (count($errors) > 0)
        <!-- Список ошибок формы -->
        <div class="alert alert-danger">
            <strong>Упс! Что-то пошло не так!</strong>

            <br><br>

            <ul>
                @@foreach ($errors->all() as $error)
                    <li>@{{ $error }}</li>
                    @@endforeach
            </ul>
        </div>
     @@endif

    <div class="card mb-4">
        <h6 class="card-header">
            @{{ (isset($oModel)?'Редактирование ':'Создание ').'записи' }}
        </h6>
        <div class="card-body">
            <form method="post" class="form-horizontal" action="{{ '{{' }} route("admin_{{ strtolower($sModelName) }}_store") }}" enctype="multipart/form-data" >
                @{{ csrf_field() }}
                @@if(isset($oModel))
                    <input type="hidden" name="id" value="@{{ $oModel->id }}">
                @@endif
@php
    $aInputTypes = $oRequest->input('show_type',[]);
    $aOptions = $oRequest->input('select_options',[]);
    $aUneditedFields = ['id','created_at','updated_at',$oRequest->input('published_field',NULL)];
    if(isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))
        $aUneditedFields[] = $aModelInfo['order_field'];
@endphp
@foreach($aModelInfo['fields'] as $sField)
{{----}}@if(!in_array($sField,$aUneditedFields) && isset($aInputTypes[$sField]))
                <div class="form-group">
                    <label class="form-label">{{ \Illuminate\Support\Str::title($sField) }}</label>
{{--    --}}@if($aInputTypes[$sField]=='text')
                    <input type="text" name="{{ $sField }}" class="form-control{{ '{{' }} $errors->has("{{ $sField }}") ? " is-invalid" : "" }}" value="{{ '{{' }} old("{{ $sField }}",false)?old("{{ $sField }}"):(isset($oModel)?$oModel->{{ $sField }}:'') }}">
{{--    --}}@endif
{{--    --}}@if($aInputTypes[$sField]=='number')
                    <input type="number" name="{{ $sField }}" class="form-control{{ '{{' }} $errors->has("{{ $sField }}") ? " is-invalid" : "" }}" value="{{ '{{' }} old("{{ $sField }}",false)?old("{{ $sField }}"):(isset($oModel)?$oModel->{{ $sField }}:'') }}">
{{--    --}}@endif
{{--    --}}@if($aInputTypes[$sField]=='file')
                    <div class="filemanager" data-name="{{ $sField }}" data-value="{{ '{{' }} old("{{ $sField }}",false)?old("{{ $sField }}"):(isset($oModel)?$oModel->{{ $sField }}:'') }}"></div>
{{--    --}}@endif
{{--    --}}@if($aInputTypes[$sField]=='image')
                    <div class="filemanager-img" data-name="{{ $sField }}" data-value="{{ '{{' }} old("{{ $sField }}",false)?old("{{ $sField }}"):(isset($oModel)?$oModel->{{ $sField }}:'') }}"></div>
{{--    --}}@endif
{{--    --}}@if($aInputTypes[$sField]=='textarea')
                    <textarea name="{{ $sField }}" id="{{ $sField }}_editor" class="form-control{{ '{{' }} $errors->has("{{ $sField }}") ? " is-invalid" : "" }}">{{ '{{' }} old("{{ $sField }}",false)?old("{{ $sField }}"):(isset($oModel)?$oModel->{{ $sField }}:'') }}</textarea>
{{--    --}}@endif
{{--    --}}@if($aInputTypes[$sField]=='select')
                    <select name="{{ $sField }}" data-placeholder="Выберите вид" class="select2 custom-select{{ '{{' }} $errors->has("{{ $sField }}") ? " is-invalid" : "" }}">
{{--        --}}@if(isset($aOptions[$sField]))
{{--            --}}@foreach($aOptions[$sField]['value'] as $iKey=>$sOption)
                         <option value="{{ $sOption }}" {{ '{{' }} old("{{ $sField }}",false)?("{{ $sOption }}"==old("{{ $sField }}")?'selected':''):((isset($oModel) && $oModel->{{ $sField }}=="{{ $sOption }}")?"selected":'') }}>{{ $aOptions[$sField]['name'][$iKey] }}</option>
{{--            --}}@endforeach
{{--        --}}@endif
                    </select>
{{--    --}}@endif
                    <span class="invalid-feedback">
                        <strong>{{ '{{' }} $errors->first("{{ $sField }}") }}</strong>
                    </span>
                </div>
{{----}}@endif
@endforeach
@if(isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))
                <div class="form-group">
                    <label class="form-label">Очередность</label>
                    <input type="number" name="{{ $aModelInfo['order_field'] }}" class="form-control{{ '{{' }} $errors->has("{{ $aModelInfo['order_field'] }}") ? " is-invalid" : "" }}" value="{{ '{{' }} old("{{ $aModelInfo['order_field'] }}",false)?old("{{ $aModelInfo['order_field'] }}"):(isset($oModel) ? $oModel->{{ $aModelInfo['order_field'] }} : (isset($iRecordCount) ? $iRecordCount : '')) }}">
                    <span class="invalid-feedback">
                            <strong>{{ '{{' }} $errors->first("{{ $aModelInfo['order_field'] }}") }}</strong>
                    </span>
                </div>
@endif
@foreach($aRelationships as $aRelationData)
{{----}}@if($aRelationData['type']=='belongsTo')
                <div class="form-group">
                    <label class="form-label">{{ \Illuminate\Support\Str::title($aRelationData['name']) }}</label>
                    <select name="{{ $aRelationData['foreign_key'] }}" data-placeholder="Выберите родителя" class="select2 custom-select{{ '{{' }} $errors->has("{{ $aRelationData['foreign_key'] }}") ? " has-error" : "" }}">
                        @@foreach($o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}s as $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }})
                        <option value="{{ '{{' }} $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->{{ $aRelationData['other_key'] }} }}" {{ '{{' }} old("{{ $aRelationData['foreign_key'] }}",false)?($o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->{{ $aRelationData['other_key'] }}==old("{{ $aRelationData['foreign_key'] }}")?"selected":""):((isset($oModel) && $oModel->{{ $aRelationData['foreign_key'] }}==$o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->{{ $aRelationData['other_key'] }})?"selected":'')  }}>{{ '{{' }} $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->{{ $aRelationData['other_key'] }} }}</option>
                        @@endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ '{{' }} $errors->first("{{ $aRelationData['foreign_key'] }}") }}</strong>
                    </span>
                </div>
{{----}}@endif
{{----}}@if($aRelationData['type']=='belongsToMany')
                <div class="form-group">
                    <label class="form-label">{{ \Illuminate\Support\Str::title($aRelationData['name']) }}</label>
                    <select name="{{ $aRelationData['name'] }}[]" data-placeholder="Выберите категории" class="select2 custom-select{{ '{{' }} $errors->has("{{ $aRelationData['name'] }}") ? " has-error" : "" }}" multiple tabindex="4">
                        @@foreach($o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}s as $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }})
                        <option value="{{ '{{' }} $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->id }}" {{ '{{' }} old("{{ $aRelationData['name'] }}",false)?(in_array($o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->id,old("{{ $aRelationData['name'] }}",[]))?"selected":""):(in_array($o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->id,$aExist{{ \Illuminate\Support\Str::title($aRelationData['class']) }}Id)?"selected":'') }}>{{ '{{' }} $o{{ \Illuminate\Support\Str::title($aRelationData['class']) }}->id }}</option>
                        @@endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ '{{' }} $errors->first("{{ $aRelationData['name'] }}") }}</strong>
                    </span>
                </div>
{{----}}@endif
@endforeach
@if($sImageType=='only-main' || $sImageType=='full')
                <div class="row">
                    <div class="col-md-9">

                        <div class="cropper-example-container">
                            <img id="cropper-example-image" {{ !empty($aModelInfo['image_proportion']['width'])?'data-crop_x='.$aModelInfo['image_proportion']['width']:'' }} {{ !empty($aModelInfo['image_proportion']['height'])?'data-crop_y='.$aModelInfo['image_proportion']['height']:'' }} src="@{{ (isset($oModel) && !empty($oModel->image))?$oModel->image->path:"/admin/img/default.jpg" }}" alt="Picture">
                        </div>

                    </div>
                    <div class="col-md-3">

                        <!-- Preview -->
                        <div class="mb-3 clearfix">
                            <h6>Preview {{ !empty($aModelInfo['image_size']['width'])?$aModelInfo['image_size']['width']:'' }}x{{ !empty($aModelInfo['image_size']['height'])?$aModelInfo['image_size']['height']:'' }}</h6>
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
@endif
@if($sImageType=='only-addition' || $sImageType=='full')
                <h6 class="mt-4">Изображения</h6>
                <div class="form-group drop-group" data-name="images_id" data-maxwidth="1920" data-folder="{{ $sModelName }}" data-action="{{ '{{' }} route('LoadImages') }}" {!! !empty($aModelInfo['image_size']['width'])?'data-width="'.$aModelInfo['image_size']['width'].'"':'' !!} {!! !empty($aModelInfo['image_size']['height'])?'data-height="'.$aModelInfo['image_size']['height'].'"':'' !!}>
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
                        @@if(isset($oModel))
                            @@foreach($oModel->images as $oImage)
                                @@if($oImage->is_main == true)
                                    @@continue
                                @@endif
                                <li class="flow-file list-group-item flow-file-@{{ $oImage->id }}">
                                    <input type="hidden" name="images_id[]" value="@{{ $oImage->id }}">
                                    <div class="flow-progress media">
                                        <div class="media-body">
                                            <div><img src="@{{ $oImage->path }}" class="mr-2" alt="" height="48px">
                                                <strong class="flow-file-name">@{{ $oImage->path }}</strong> - <em class="flow-file-progress">(loaded)</em>
                                            </div>

                                        </div>
                                        <div class="ml-3 align-self-center">  <button type="button" data-file="flow-file-@{{ $oImage->id }}" class="delete-file flow-file-cancel btn btn-sm icon-btn btn-outline-danger"><i class="ion ion-md-close"></i></button></div>
                                    </div>
                                </li>
                            @@endforeach
                        @@endif
                    </ul>
                </div>
@endif

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" >@{{ (isset($oModel)?'Сохранить ':'Создать ').'запись' }}</button>
                </div>
            </form>
        </div>
    </div>
@@endsection

@@section('css')
@if($sImageType=='only-main' || $sImageType=='full')
    <link href="@{{ asset('/admin/libs/cropper/cropper.css') }}" rel="stylesheet">
@endif
@if($sImageType=='only-addition' || $sImageType=='full')
    <link href="@{{ asset('/admin/libs/flow-js/flow.css') }}" rel="stylesheet">
@endif
@@endsection

@@section('scripts')
@if(in_array('textarea',$aInputTypes))
    <script src="@{{ asset('/admin/libs/tinymce/tinymce.min.js') }}"></script>
@endif
@if($sImageType=='only-main' || $sImageType=='full')
    <script src="@{{ asset('/admin/libs/cropper/cropper.js') }}"></script>
    <script src="@{{ asset('/admin/js/cropimage.js') }}"></script>
@endif
@if($sImageType=='only-addition' || $sImageType=='full')
    <script src="@{{ asset('/admin/libs/flow-js/flow.js') }}"></script>
@endif
    <script>
@if(in_array('textarea',$aInputTypes))
@foreach($aInputTypes as $sCurField=>$aInputType)
@if($aInputType=='textarea')
        tinymce.init({...tiny_mce_conf, selector: '{{'#'.$sCurField}}_editor'});
@endif
@endforeach
@endif
    </script>
@@endsection
