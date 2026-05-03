@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование контакта':'Создание контакта')

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

	{{ csrf_field() }}
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Заполните поля <small>для добавления нового контакта.</small></h5>
				</div>
				<div class="ibox-content">
					<form method="post" class="form-horizontal" action="{{route('admin_contact_store')}}" enctype="multipart/form-data" >
						{{ csrf_field() }}
						@if(isset($oModel))
							<input type="hidden" name="id" value="{{ $oModel->id }}">
						@endif


						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">Название</label>
							<div class="col-lg-11">
								<input type="text" name="name" class="form-control" value="{{ isset($oModel)?$oModel->name:old('name') }}">
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
						</div>
						<div class="form-group {{ $errors->has('value') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">Значение</label>
							<div class="col-lg-11">
								<input type="text" name="value" class="form-control" value="{{ isset($oModel)?$oModel->value:old('value') }}">
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('value') }}</strong>
                            </span>
						</div>
						<div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
							<label class="col-lg-1  control-label">Категория</label>
							<div class="col-lg-11">
								<select name="category_id" data-placeholder="Выберите категорию" class="chosen-select" tabindex="4">
									@foreach($oCategories as $oCategory)
										<option value="{{ $oCategory->id }}" {{ isset($oModel) && $oCategory->id==$oModel->category_id ? 'selected':($oCategory->id==old('category_id')?'selected':'') }}>{{ $oCategory->name }}</option>
									@endforeach
								</select>
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('category_id') }}</strong>
                            </span>
						</div>
						<div class="form-group {{ $errors->has('order') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">Очередность</label>
							<div class="col-lg-11">
								<input type="text" name="order" class="form-control" value="{{ isset($oModel)?$oModel->order:(isset($iContactCount)?$iContactCount:old('order')) }}">
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('order') }}</strong>
                            </span>
						</div>
						{{--<div class="form-group {{ $errors->has('helm') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">Руль</label>
							<div class="col-lg-11">
								<select name="helm" data-placeholder="Выберите вид руля" class="chosen-select">
									<option value="helm_left" {{ (isset($oProduct) && $oProduct->helm=='helm_left')?'selected':($oProduct->helm==old('heml')?'selected':'') }}>Левый</option>
									<option value="helm_right" {{ (isset($oProduct) && $oProduct->helm=='helm_right')?'selected':($oProduct->helm==old('heml')?'selected':'') }}>Правый</option>
								</select>
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('helm') }}</strong>
                            </span>
						</div>--}}



						<div class="hr-line-dashed"></div>

						<div class="form-group">
							<div class="col-lg-12 text-right">
								<button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Опубликовать</button>
							</div>
						</div>

						<input type="hidden" name="del_image_ids" id="removeImgIds">
					</form>
				</div>
			</div>
		</div>

	</div>
@endsection

@section('css')
	<link href="{{ asset('/admin/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('scripts')
	<script src="{{ asset('/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>

	<script>
		$('.chosen-select').chosen({width: "100%"});
	</script>
@endsection
