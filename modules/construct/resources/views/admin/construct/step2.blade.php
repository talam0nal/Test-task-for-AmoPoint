@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование модели':'Создание модели')

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
<div class="py-3 mb-2">
    <h4 class="font-weight-bold mb-2">
        Создание модуля
    </h4>
</div>

<div class="card mb-4">
    <div id="smartwizard-3" class="smartwizard-example sw-main sw-theme-default">
        <ul class="card px-4 pt-3 mb-3 nav nav-tabs step-anchor">
            <li class="nav-item done">
                <a class="mb-3 nav-link">
                    <span class="sw-done-icon ion ion-md-checkmark"></span>
                    <span class="sw-number">1</span>
                    <div class="text-muted small">ШАГ 1</div>
                    Миграция
                </a>
            </li>
            <li class="nav-item active">
                <a class="mb-3 nav-link">
                    <span class="sw-done-icon ion ion-md-checkmark"></span>
                    <span class="sw-number">2</span>
                    <div class="text-muted small">ШАГ 2</div>
                    Модель
                </a>
            </li>
            <li class="nav-item">
                <a class="mb-3 nav-link">
                    <span class="sw-done-icon ion ion-md-checkmark"></span>
                    <span class="sw-number">3</span>
                    <div class="text-muted small">ШАГ 3</div>
                    Контроллер
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <form method="post" class="form-horizontal" action="{{route('admin_construct_store_model')}}" enctype="multipart/form-data" >
            {{ csrf_field() }}
            @if(isset($oModel))
                <input type="hidden" name="id" value="{{ $oModel->id }}">
            @endif
            <div class="form-group mb-4">
                <label class="form-label">Название модели</label>
                <input type="text" name="model_name" class="form-control{{ $errors->has('model_name') ? ' is-invalid' : '' }}" value="{{ isset($sModelName)?$sModelName:old('model_name') }}">
                <div class="invalid-feedback">
                    {{ $errors->first('model_name') }}
                </div>
            </div>
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Поля</th>
                        <th class="text-center">Массовое присвоение</th>
                        <th class="text-center">Скрывать при выводе</th>
                        <th class="text-center">Slug</th>
                        <th class="text-center">Для генерации Slug</th>
                        <th class="text-center">Для сортировки</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($aFields as $sField)
                        <tr>
                            <th>{{ $sField }}</th>
                            @if($sField!=='created_at' && $sField!=='updated_at' && $sField!=='id')
                                <td class="text-center"><input class="form-check-input" type="checkbox" value="{{ $sField }}" name="fillable[]" checked></td>
                                <td class="text-center"><input class="form-check-input" type="checkbox" value="{{ $sField }}" name="hidden[]" {{ (in_array($sField,old('hidden',[])))?'checked':'' }}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="{{ $sField }}" name="slug" {{ $sField==old('slug','')?'checked':'' }}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="{{ $sField }}" name="slug_source" {{ $sField==old('slug_source','')?'checked':'' }}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="{{ $sField }}" name="order" {{ $sField==old('order','')?'checked':'' }}></td>
                            @else
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center"><input class="form-check-input" type="checkbox" value="{{ $sField }}" name="hidden[]"  {{ (in_array($sField,old('hidden',[])))?'checked':'' }}></td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 form-group">
                <label class="form-label">Работа с изображениями</label>
                <select name="image" id="image-type" data-placeholder="Выберите категории" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" tabindex="4">
                    <option value="no-image" {{ ('no-image'==old('image','')?'selected':'') }}>Модель не использует изображения</option>
                    <option value="only-main" {{ ('only-main'==old('image','')?'selected':'') }}>Модель использует только одно изображение</option>
                    <option value="only-addition" {{ ('only-addition'==old('image','')?'selected':'') }}>Модель использует несколько изображений, не выделяя главного</option>
                    <option value="full" {{ ('full'==old('image','')?'selected':'') }}>Модель использует одно главное и несколько дополнительных изображений</option>
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('image') }}
                </div>
            </div>

            <hr>
            <div class="form-group">
                <h6>Отношения</h6>
                <div id="relationships" class="col-lg-11">
                    @if(old('relation',false))
                        @foreach(old('relation') as $iKey=>$aRelationInfo)
                            <div class="relation">
                                Текущий класс
                                <select name="relation[{{ $iKey }}][type]" class="relation_type">
                                    <option value="hasOne" {{ $aRelationInfo['type']==='hasOne'?'selected':'' }}>владеет одним классом (hasOne)</option>
                                    <option value="hasMany" {{ $aRelationInfo['type']==='hasMany'?'selected':'' }}>владеет многими классами (hasMany)</option>
                                    <option value="belongsTo" {{ $aRelationInfo['type']==='belongsTo'?'selected':'' }}>принадлежит одному классу (belongsTo)</option>
                                    <option value="belongsToMany" {{ $aRelationInfo['type']==='belongsToMany'?'selected':'' }}>принадлежит ко многим классам (belongsToMany)</option>
                                </select>
                                : <select name="relation[{{ $iKey }}][class]" class="target_class">
                                    @foreach($aClasses as $sClass)
                                        <option value="{{ strtolower($sClass) }}" {{ $aRelationInfo['class']===strtolower($sClass)?'selected':'' }}>{{ $sClass }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="relation[{{ $iKey }}][join_table]" class="join_table"
                                       placeholder="через таблицу" value="{{ isset($aRelationInfo['join_table'])?$aRelationInfo['join_table']:'' }}"
                                        {!! $aRelationInfo['type']!=='belongsToMany'?'disabled="disabled"':'' !!}
                                        {!! $errors->has('relation.'.$iKey.'.join_table') ? ' style="border: solid 1px red"' : '' !!}>

                                <input type="text" name="relation[{{ $iKey }}][foreign_key]" class="foreign_key" placeholder="внешний ключ"
                                       value="{{ isset($aRelationInfo['foreign_key'])?$aRelationInfo['foreign_key']:'' }}" {!! $aRelationInfo['type']==='belongsTo'?'disabled="disabled" style="display: none"':'' !!}>

                                <select name="relation[{{ $iKey }}][foreign_key]" class="foreign_key" {!! $aRelationInfo['type']!=='belongsTo'?'disabled="disabled" style="display: none"':'' !!}>
                                    @foreach($aFields as $sField)
                                        <option value="{{ $sField }}" {{ (isset($aRelationInfo['foreign_key']) && $sField==$aRelationInfo['foreign_key'])?'selected':'' }}>{{ $sField }}</option>
                                    @endforeach
                                </select>

                                <input type="text" name="relation[{{ $iKey }}][other_key]" class="other_key"
                                       placeholder="связанный ключ" value="{{ isset($aRelationInfo['other_key'])?$aRelationInfo['other_key']:'' }}"
                                        {!! ($aRelationInfo['type']!=='belongsTo' && $aRelationInfo['type']!=='belongsToMany')?'disabled="disabled" style="display: none"':'' !!}>

                                <select name="relation[{{ $iKey }}][other_key]" class="other_key" {!! ($aRelationInfo['type']!=='hasOne' && $aRelationInfo['type']!=='hasMany')?'disabled="disabled" style="display: none"':'' !!}>
                                    @foreach($aFields as $sField)
                                        <option value="{{ $sField }}" {{ (isset($aRelationInfo['other_key']) && $sField==$aRelationInfo['other_key'])?'selected':'' }}>{{ $sField }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="relation[{{ $iKey }}][name]" placeholder="название отношения" value="{{ $aRelationInfo['name'] }}"
                                        {!! $errors->has('relation.'.$iKey.'.name') ? ' style="border: solid 1px red"' : '' !!}>
                                <button class="remove">X</button>
                            </div>
                        @endforeach
                        @php
                            $aOldRelations = old('relation');
							$iLastKey = max(array_keys($aOldRelations));
                        @endphp
                    @endif
                </div>
            </div>
            <div class="form-group">
                <button id="add_relationship" class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="button">Добавить отношение</button>
            </div>
            <div class="form-group text-right">
                <button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Создать модель</button>
            </div>
        </form>
    </div>
</div>

<div id="relationship_example" style="display: none">
    <div class="relation form-row">
        <div class="form-group col-md-2">
            <label class="form-label"> Текущий класс</label>
            <select name="relation[0][type]" class="relation_type form-control">
                <option value="hasOne">hasOne - владеет одним классом</option>
                <option value="hasMany">hasMany - владеет многими классами</option>
                <option value="belongsTo">belongsTo - принадлежит одному классу</option>
                <option value="belongsToMany">belongsToMany - принадлежит ко многим классам</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">&nbsp;</label>
            <select name="relation[0][class]" class="target_class form-control">
                @foreach($aClasses as $sClass)
                    <option value="{{ strtolower($sClass) }}">{{ $sClass }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">&nbsp;</label>
            <input type="text" name="relation[0][join_table]" class="join_table form-control" placeholder="через таблицу" disabled="disabled">
        </div>
        <div class="form-group col-md-1 foreign_key_col">
            <label class="form-label">&nbsp;</label>
            <input type="text" name="relation[0][foreign_key]" class="foreign_key form-control" placeholder="внешний ключ" value="{{ strtolower($aClasses[0]) }}_id">
            <select name="relation[0][foreign_key]" class="foreign_key form-control" disabled style="display: none">
                @foreach($aFields as $sField)
                    <option value="{{ $sField }}" {{ $sField==strtolower($aClasses[0]).'_id'?'selected':'' }}>{{ $sField }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-2 other_key_select">
            <label class="form-label">&nbsp;</label>
            <input type="text" name="relation[0][other_key]" class="other_key form-control" placeholder="связанный ключ" value="id" disabled style="display: none">

            <select name="relation[0][other_key]" class="other_key form-control">
                @foreach($aFields as $sField)
                    <option value="{{ $sField }}">{{ $sField }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">&nbsp;</label>
            <input type="text" name="relation[0][name]" placeholder="название отношения" class="form-control">
        </div>
        <div class="form-group col-md-1">
            <button type="button" class="btn icon-btn btn-sm btn-outline-dark remove" style="margin-top: 31px;">
                <span class="ion ion-md-trash"></span>
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            var count = {{ isset($iLastKey)?$iLastKey:0 }};
            var addition_sizes_count = 0;
            $('#add_relationship').click(function(){
                var row = $('#relationship_example .relation').clone();
                //var count = $('.fields .row_set').length;
                count++;
                row.find('input, select').each(function(){
                    var index = $(this).attr('name').replace('0',count);
                    $(this).attr('name',index);
                })
                $('#relationships').append(row);
            });
            $('#relationships').on('click','.remove',function() {
                $(this).closest('.relation').remove();
            });
            $('#relationships').on('change','.relation_type',function() {
                var model_name = $('input[name=model_name]').val();
                var target_model_name =  $(this).closest('.relation').find('.target_class option:selected').val();
                if($(this).find('option:selected').val()=='belongsToMany')
                {
                    $(this).closest('.relation').find('.join_table').val(model_name+'s_'+target_model_name+'s');
                    $(this).closest('.relation').find('.join_table').prop('disabled',false);
                    $(this).closest('.relation').find('input.foreign_key').prop('disabled',false).val(model_name+'_id').show();
                    $(this).closest('.relation').find('select.foreign_key').prop('disabled',true).hide();
                    $(this).closest('.relation').find('input.other_key').prop('disabled',false).val(target_model_name+'_id').show();
                    $(this).closest('.relation').find('select.other_key').prop('disabled',true).hide();
                }
                if($(this).find('option:selected').val()=='belongsTo')
                {
                    $(this).closest('.relation').find('.join_table').val('');
                    $(this).closest('.relation').find('.join_table').prop('disabled',true);
                    $(this).closest('.relation').find('input.foreign_key').prop('disabled',true).hide();//.val(model_name+'_id');
                    $(this).closest('.relation').find('select.foreign_key').prop('disabled',false).show().val(target_model_name+'_id');
                    $(this).closest('.relation').find('input.other_key').prop('disabled',false).val('id').show();
                    $(this).closest('.relation').find('select.other_key').prop('disabled',true).hide();
                }
                if($(this).find('option:selected').val()=='hasOne' || $(this).find('option:selected').val()=='hasMany')
                {
                    $(this).closest('.relation').find('.join_table').val('');
                    $(this).closest('.relation').find('.join_table').prop('disabled',true);
                    $(this).closest('.relation').find('input.foreign_key').prop('disabled',false).val(model_name+'_id').show();
                    $(this).closest('.relation').find('select.foreign_key').prop('disabled',true).hide();
                    $(this).closest('.relation').find('input.other_key').prop('disabled',true).hide();
                    $(this).closest('.relation').find('select.other_key').prop('disabled',false).show().val('id');
                }
            });
            $('#relationships').on('change','.target_class',function() {
                var model_name = $('input[name=model_name]').val();
                var target_model_name =  $(this).find('option:selected').val();
                var relation_type = $(this).closest('.relation').find('.relation_type option:selected').val();
                if(relation_type=='hasOne' || relation_type=='hasMany')
                {
                    $(this).closest('.relation').find('.foreign_key').val(model_name+'_id');
                    $(this).closest('.relation').find('.other_key').val('id');
                }
                if(relation_type=='belongsTo')
                {
                    $(this).closest('.relation').find('.foreign_key').val(target_model_name+'_id');
                    $(this).closest('.relation').find('.other_key').val('id');
                }
                if(relation_type=='belongsToMany')
                {
                    $(this).closest('.relation').find('.foreign_key').val(model_name+'_id');
                    $(this).closest('.relation').find('.other_key').val(target_model_name+'_id');
                    $(this).closest('.relation').find('.join_table').val(model_name+'s_'+target_model_name+'s');
                }

            });
            $('#image-type').change(function(){
                var value = $(this).find('option:selected').val();
                if(value=='only-main' || value=='only-addition' || value=='full')
                {
                    $('.proportions').remove();
                    $(this).after('<div class="proportions">'+
                                    '<h6 class="mt-4">Задайте размер изображения</h6>'+
                            'Width: <input type="number" min="1" name="image_proportion[width]"> '+
                            'Height: <input type="number" min="1" name="image_proportion[height]">' +
                            '<div class="addition-sizes"></div>' +
                            '<input type="button" class="add_size" value="Добавить размер">' +
                            '</div>');
                }
                else
                {
                    $('.proportions').remove();
                }
            });
            $('.mt-4.form-group').on('click','.add_size',function(){

                $(this).siblings('.addition-sizes').append('<div class="addition-size">' +
                        'Name: <input type="text" name="addition_sizes['+addition_sizes_count+'][name]" value="">' +
                        'Width: <input type="number" min="1" name="addition_sizes['+addition_sizes_count+'][width]">' +
                        'Height: <input type="number" min="1" name="addition_sizes['+addition_sizes_count+'][height]">' +
                        '<input type="button" class="remove_size" value="X">' +
                        '</div>');
                addition_sizes_count++;
            });
            $('.mt-4.form-group').on('click','.remove_size',function(){
                $(this).closest('.addition-size').remove();
            });
        });
    </script>
@endsection