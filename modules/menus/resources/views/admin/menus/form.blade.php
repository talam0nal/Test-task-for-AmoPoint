@extends('admin.layout')

@section('page_name', (isset($oModel)?'Редактирование ':'Создание ').'пункта меню')

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
		{{ (isset($oModel) ? 'Редактирование ' : 'Создание ').'пункта меню' }}
	</h6>
	<div class="card-body">
		<form method="post" class="form-horizontal" action="{{route('admin_menus_store')}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			@if(isset($oModel))
				<input type="hidden" name="id" value="{{ $oModel->id }}">
			@endif

			<div class="form-row">
				<div class="form-group col-md-6">
					<label class="form-label">Название</label>
					<input type="text" name="name" class="form-control" value="{{ isset($oModel)?$oModel->name:old('name') }}">
					<div class="invalid-feedback">
						{{ $errors->first('name') }}
					</div>
				</div>
				<div class="form-group col-md-6">
					<label class="form-label">URL</label>
					<input type="text" name="link" class="form-control" value="{{ isset($oModel)?$oModel->link:old('link') }}">
					<div class="invalid-feedback">
						{{ $errors->first('link') }}
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label class="form-label">Тип меню</label>
					<select name="type" data-placeholder="Выберите тип" class="custom-select">
						{{ $prvopt = isset($oModel)?$oModel->type:old('type') }}
						@foreach($types as $opid=>$opt)
							<option value="{{$opid}}"{{($prvopt==$opid)?' selected="selected"':''}}>{{$opt}}</option>
						@endforeach
					</select>
					<div class="invalid-feedback">
						{{ $errors->first('type') }}
					</div>
				</div>
				<div class="form-group col-md-6">
					<label class="form-label">Порядок</label>
					<input type="text" name="order" class="form-control" value="{{ old('order',$oModel->order ?? $iCount ?? 0) }}">
					<div class="invalid-feedback">
						{{ $errors->first('order') }}
					</div>
				</div>
			</div>

			<div class="form-group text-right">
				<button type="submit" class="btn btn-primary">Сохранить</button>
			</div>
		</form>
	</div>
</div>
@endsection

@section('css')

@endsection

@section('scripts')


	<script>

	</script>
@endsection
