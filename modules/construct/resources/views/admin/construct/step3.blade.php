@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование контроллера':'Создание контроллера')

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
                <li class="nav-item done">
                    <a class="mb-3 nav-link">
                        <span class="sw-done-icon ion ion-md-checkmark"></span>
                        <span class="sw-number">2</span>
                        <div class="text-muted small">ШАГ 2</div>
                        Модель
                    </a>
                </li>
                <li class="nav-item active">
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
            <form method="post" class="form-horizontal" action="{{route('admin_construct_store_controller')}}" enctype="multipart/form-data" >
                {{ csrf_field() }}
                @if(isset($oModel))
                    <input type="hidden" name="id" value="{{ $oModel->id }}">
                @endif
                <div class="form-group mb-4">
                    <label class="form-label">Название контроллера <small>слово "Controller" допишется автоматически</small></label>
                    <input type="text" name="controller_name" class="form-control{{ $errors->has('model_name') ? ' is-invalid' : '' }}" value="{{ isset($sModelName)?$sModelName:old('model_name') }}">
                    <div class="invalid-feedback">
                        {{ $errors->first('model_name') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Поле флага публикации/активации</label>
                    <select id="published_select_field" name="published_field" data-placeholder="Выберите вид руля" class="select2 form-control{{ $errors->has('published_field') ? ' is-invalid' : '' }}">
                        <option value="{{ NULL }}" {{ empty(old('published_field'))?'selected':'' }}>Нет</option>
                        @foreach($aAllFields as $sField)
                            <option value="{{ $sField }}" {{ ($sField==old('published_field'))?'selected':'' }}>{{ $sField }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('published_field') }}</strong>
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">Выводить в общем списке</label>
                    <select name="index_fields[]" data-placeholder="Выберите поля" class="select2 form-control{{ $errors->has('index_fields') ? ' is-invalid' : '' }}" multiple tabindex="4">
                        @foreach($aAllFields as $sField)
                            <option value="{{ $sField }}" {{ in_array($sField,$aExistIndexFields) ? 'selected':(in_array($sField,old('index_fields',[]))?'selected':'') }}>{{ $sField }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('index_fields') }}</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Использовать для поиска</label>
                    <select name="search_fields[]" data-placeholder="Выберите поля" class="select2 form-control{{ $errors->has('search_fields') ? ' is-invalid' : '' }}" multiple tabindex="4">
                        @foreach($aAllFields as $sField)
                            <option value="{{ $sField }}" {{ in_array($sField,$aExistIndexFields) ? 'selected':(in_array($sField,old('search_fields',[]))?'selected':'') }}>{{ $sField }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('search_fields') }}</strong>
                    </span>
                </div>

                <div class="fields">
                    <div class="row">
                        <div class="col-1"><strong>Поле</strong></div>
                        <div class="col-6"><strong>Правила валидации</strong></div>
                        <div class="col-5"><strong>Способ ввода</strong></div>
                    </div>
                    @foreach($aPublicFields as $iKey=>$sField)
                        <hr>
                        <div class="field_info row {{ $errors->has('fields') ? ' has-error' : '' }}" id="{{ $sField }}_field">
                            <label class="col-lg-1  control-label">{{ $sField }}</label>
                            <div class="col-lg-6">
                                {{--<select name="validation_rules[{{ $iKey }}][]" data-placeholder="Выберите правила" class="chosen-select" multiple tabindex="4">
									@foreach($aFields as $sField)
										<option value="{{ $sField }}" {{ in_array($sField,$aExistIndexFields) ? 'selected':(in_array($sField,old('search_fields',[]))?'selected':'') }}>{{ $sField }}</option>
									@endforeach
								</select>--}}
                                @if($sField!='created_at' && $sField!='updated_at' && $sField!='id')
                                    <div class="valid_rules" data-field_name="{{ $sField }}">
                                        @if(old('validation_rules.'.$sField,false))
                                            @foreach(old('validation_rules.'.$sField.'.rules') as $sRule)
                                                <div class="one_rule">
                                                    <select class="rule_list form-control form-control-sm" style="width: 30%; display: inline" name="validation_rules[{{ $sField }}][rules][]">
                                                        @foreach($aRules as $sOneRule=>$iParam)
                                                            <option value="{{ $sOneRule }}" data-params={{ $iParam }} {{ $sRule==$sOneRule?'selected':'' }}>{{ $sOneRule }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($aRules[$sRule]==1)
                                                        <input type="text" class="rule_param form-control form-control-sm" style="width: 30%; display: inline" name="validation_rules[{{ $sField }}][params][{{ $sRule }}]" value="{{ old('validation_rules.'.$sField.'.params.'.$sRule) }}">
                                                    @endif
                                                    <button type="button" class="btn icon-btn btn-sm btn-outline-dark remove_rule">
                                                        <span class="ion ion-md-trash"></span>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <input type="button" value="Добавить правило валидации" class="add_valid btn btn-sm btn-outline-info">
                                @endif
                            </div>
                            <div class="col-lg-5">
                                <select class="input_form_type form-control form-control-sm" style="width: 50%" name="show_type[{{ $sField }}]">
                                    <option value="text" {{ old('show_type.'.$sField)=='text'?'selected':'' }}>Текстовое поле</option>
                                    <option value="number" {{ old('show_type.'.$sField)=='number'?'selected':'' }}>Цифровое поле</option>
                                    <option value="textarea" {{ old('show_type.'.$sField)=='textarea'?'selected':'' }}>Текстовая область</option>
                                    <option value="select" {{ old('show_type.'.$sField)=='select'?'selected':'' }}>Список</option>
                                    <option value="file" {{ old('show_type.'.$sField)=='file'?'selected':'' }}>Файл</option>
                                    <option value="image" {{ old('show_type.'.$sField)=='image'?'selected':'' }}>Изображение</option>
                                </select>
                                <div class="options" {!! old('show_type.'.$sField)!='select'?'style="display: none"':'' !!} data-field_name="{{ $sField }}">
                                    <div>
                                        <input type="text" placeholder="value" name="select_options[{{ $sField }}][value][1]" value="{{ old('select_options.'.$sField.'.value.1') }}">
                                        <input type="text" placeholder="name" name="select_options[{{ $sField }}][name][1]" value="{{ old('select_options.'.$sField.'.name.1') }}">
                                    </div>
                                    <div>
                                        <input type="text" placeholder="value" name="select_options[{{ $sField }}][value][2]" value="{{ old('select_options.'.$sField.'.value.2') }}">
                                        <input type="text" placeholder="name" name="select_options[{{ $sField }}][name][2]" value="{{ old('select_options.'.$sField.'.name.2') }}">
                                    </div>
                                    @if(old('select_options.'.$sField,false))
                                        @foreach(old('select_options.'.$sField.'.value') as $iOptionKey=>$aSelectValue)
                                            @if($iOptionKey!==1 && $iOptionKey!==2)
                                                <div class="option_input">
                                                    <input type="text" name="select_options[{{ $sField }}][value][{{ $iOptionKey }}]" value="{{ old('select_options.'.$sField.'.value.'.$iOptionKey) }}">
                                                    <input type="text" name="select_options[{{ $sField }}][name][{{ $iOptionKey }}]" value="{{ old('select_options.'.$sField.'.name.'.$iOptionKey) }}">
                                                    <button type="button" class="btn icon-btn btn-sm btn-outline-dark remove_option">
                                                        <span class="ion ion-md-trash"></span>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="button" value="Добавить опцию" class="add_options btn btn-sm btn-outline-info">
                                </div>
                            </div>
                            {{--<div class="col-lg-1">
								<input type="radio" value="{{ $sField }}" name="published_field">
							</div>
							<div class="col-lg-1">
								<input type="checkbox" value="{{ $sField }}" name="search_fields[]">
							</div>
							<div class="col-lg-1">
								<input type="checkbox" value="{{ $sField }}" name="index_fields[]">
							</div>--}}
                        </div>

                    @endforeach
                </div>



                <div class="form-group text-right">
                    <button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Создать контроллер</button>
                </div>
            </form>
        </div>
    </div>


    <div id="rules_list" style="display: none">
        <div class="one_rule mb-2">
            <select class="rule_list form-control form-control-sm" style="width: 30%; display: inline" name="validation_rules[field_name][rules][]">
                @foreach($aRules as $sOneRule=>$iParam)
                    <option value="{{ $sOneRule }}" data-params={{ $iParam }}>{{ $sOneRule }}</option>
                @endforeach
            </select>
            <button type="button" class="btn icon-btn btn-sm btn-outline-dark remove_rule">
                <span class="ion ion-md-trash"></span>
            </button>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/admin/libs/select2/select2.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('/admin/libs/select2/select2.js') }}"></script>

    <script>

        $('.select2').select2();

        $(document).ready(function(){
            var option_counts = Array();
            $('.add_valid').click(function(){
                var field_name = $(this).prev('.valid_rules').data('field_name');
                var rules_selection = $('#rules_list .one_rule').clone();
                rules_selection.find('.rule_list').attr('name','validation_rules['+field_name+'][rules][]');
                $(this).prev('.valid_rules').append(rules_selection);
            });
            $('.valid_rules').on('click','.remove_rule',function(){
                $(this).closest('.one_rule').remove();
            });
            $('.valid_rules').on('change','.rule_list',function(){
                var option = $(this).find('option:selected');
                var field_name = $(this).closest('.valid_rules').data('field_name');
                if(option.data('params')==1 && $(this).next('.rule_param').length==0)
                {
                    $(this).after('<input type="text" class="rule_param form-control form-control-sm" style="width: 30%; display: inline" name="validation_rules['+field_name+'][params]['+option.val()+']">');
                }
                if(option.data('params')==0 && $(this).next('.rule_param').length!=0)
                {
                    $(this).next('.rule_param').remove();
                }
            });
            $('.add_options').click(function(){
                var field_name = $(this).closest('.options').data('field_name');
                if(option_counts[field_name])
                    option_counts[field_name]++;
                else
                    option_counts[field_name] = 3;
                $(this).before('<div class="option_input">'+
                                '<input type="text" name="select_options['+field_name+'][value]['+option_counts[field_name]+']">'+
                                '<input type="text" name="select_options['+field_name+'][name]['+option_counts[field_name]+']">'+
                                '<button type="button" class="btn icon-btn btn-sm btn-outline-dark remove_option"><span class="ion ion-md-trash"></span></button>'+
                        '</div>');
            });
            $('.options').on('click','.remove_option',function(){
                $(this).closest('.option_input').remove();
            });
            $('.input_form_type').change(function(){
                var value = $(this).find('option:selected').val();
                if(value=='select')
                    $(this).next('.options').show();
                else
                {
                    $(this).next('.options').hide();
                    $(this).next('.options').find('.option_input').remove();
                }

            });
            $('#published_select_field').change(function(){
                var field_name = $(this).find('option:selected').val();
                $('.field_info').show();
                $('#'+field_name+'_field').hide();
            });
        })
    </script>
@endsection
