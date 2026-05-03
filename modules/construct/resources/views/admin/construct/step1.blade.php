@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование миграции':'Создание миграции')

@section('content')
<div class="py-3 mb-2">
    <h4 class="font-weight-bold mb-2">
       Создание модуля
    </h4>
</div>

<div class="card mb-4">
    <div id="smartwizard-3" class="smartwizard-example sw-main sw-theme-default">
        <ul class="card px-4 pt-3 mb-3 nav nav-tabs step-anchor">
            <li class="nav-item active">
                <a class="mb-3 nav-link">
                    <span class="sw-done-icon ion ion-md-checkmark"></span>
                    <span class="sw-number">1</span>
                    <div class="text-muted small">ШАГ 1</div>
                    Миграция
                </a>
            </li>
            <li class="nav-item">
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
    <h6 class="card-header">Заполните поля <small>для добавления новой миграции.</small></h6>
    <div class="card-body">
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

        <form method="post" class="form-horizontal" action="{{route('admin_construct_store_migration')}}" enctype="multipart/form-data" >
            {{ csrf_field() }}
            @if(isset($oModel))
                <input type="hidden" name="id" value="{{ $oModel->id }}">
            @endif
            <div class="form-group">
                <label class="form-label">Название таблицы</label>
                <input type="text" class="form-control{{ $errors->has('table_name') ? ' id-invalid' : '' }}" name="table_name" value="{{ isset($oModel)?$oModel->table_name:old('table_name') }}">
                <div class="invalid-feedback">
                    {{ $errors->first('table_name') }}
                </div>
            </div>


            <div class="form-group">
                <h4 class="form-label">Поля таблицы</h4>
                <div class="fields">
                    @if(old('field',false))
                        @foreach(old('field') as $iKey=>$aField)
                            <div class="form-row row_set">
                                <div class="form-group col-md-2">
                                    <label class="form-label"> Название</label>
                                    <input type="text" name="field[{{ $iKey }}][name]" class="form-control{{ $errors->has('field.'.$iKey.'.name')?' is-invalid':'' }}" value="{{ isset($aField['name'])?$aField['name']:'' }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label">Тип</label>
                                    <select name="field[{{ $iKey }}][type]" class="form-control">
                                        <option value="integer" {{ (isset($aField['type']) && $aField['type']=='integer')?'selected':'' }}>integer</option>
                                        <option value="string" {{ (isset($aField['type']) && $aField['type']=='string')?'selected':'' }}>varchar</option>
                                        <option value="boolean" {{ (isset($aField['type']) && $aField['type']=='boolean')?'selected':'' }}>boolean</option>
                                        <option value="float" {{ (isset($aField['type']) && $aField['type']=='float')?'selected':'' }}>float</option>
                                        <option value="text" {{ (isset($aField['type']) && $aField['type']=='text')?'selected':'' }}>text</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label"> Параметр 1</label>
                                    <input type="text" name="field[{{ $iKey }}][add_param_1]" value="{{ isset($aField['add_param_1'])?$aField['add_param_1']:'' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label"> Параметр 2</label>
                                    <input type="text" name="field[{{ $iKey }}][add_param_2]" value="{{ isset($aField['add_param_2'])?$aField['add_param_2']:'' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label"> По умолчанию</label>
                                    <input type="text" name="field[{{ $iKey }}][default]" value="{{ isset($aField['default'])?$aField['default']:'' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-1">
                                    <label class="switcher mt-4">
                                        <input type="checkbox" name="field[{{ $iKey }}][nullable]" value="1" {{ (isset($aField['nullable']) && $aField['nullable']==1)?'checked':'' }}>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Null</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="button" class="btn icon-btn btn-sm btn-outline-dark remove mt-4">
                                        <span class="ion ion-md-trash"></span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @php
                            $aOldFields = old('field');
                            $iLastKey = max(array_keys($aOldFields));
                        @endphp
                    @endif
                </div>
            </div>
            <div class="form-group">
                <button id="add_field" class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="button">Добавить поле</button>
            </div>
            <div class="form-group">
                <label class="switcher mt-4">
                    <input type="checkbox" name="table_timestamps" class="switcher-input{{ $errors->has('table_timestamps') ? ' is-invalid' : '' }}" value="1">
                    <span class="switcher-indicator">
                    <span class="switcher-yes"></span>
                    <span class="switcher-no"></span>
                </span>
                    <span class="switcher-label">Поля дат создания и изменения</span>
                </label>
            </div>

            <div class="form-group">
                <div class="col-lg-12 text-right">
                    <button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Создать миграцию</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div id="original" style="display: none">

    <div class="form-row row_set">
        <div class="form-group col-md-2">
            <label class="form-label"> Название</label>
            <input type="text" name="field[0][name]" class="form-control">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">Тип</label>
            <select name="field[0][type]" class="form-control">
                <option value="integer">integer</option>
                <option value="string">varchar</option>
                <option value="boolean">boolean</option>
                <option value="float">float</option>
                <option value="text">text</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label class="form-label"> Параметр 1</label>
            <input type="text" name="field[0][add_param_1]" class="form-control">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label"> Параметр 2</label>
            <input type="text" name="field[0][add_param_2]" class="form-control">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label"> По умолчанию</label>
            <input type="text" name="field[0][default]" class="form-control">
        </div>
        <div class="form-group col-md-1">
            <label class="switcher" style="margin-top: 34px;">
                <input type="checkbox" name="field[0][nullable]" class="switcher-input" value="1">
                <span class="switcher-indicator">
                    <span class="switcher-yes"></span>
                    <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Null</span>
            </label>
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
            $('#add_field').click(function(){
                var row = $('#original .row_set').clone();
                //var count = $('.fields .row_set').length;
                count++;
                row.find('input, select').each(function(){
                    var index = $(this).attr('name').replace('0',count);
                    $(this).attr('name',index);
                })
                $('.fields').append(row);
            });
            $('.fields').on('click','.remove',function() {
                $(this).closest('.row_set').remove();
            })
        });
    </script>
@endsection