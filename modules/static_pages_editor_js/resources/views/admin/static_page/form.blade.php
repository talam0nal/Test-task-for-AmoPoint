@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование статической страницы':'Создание статической страницы')

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
			{{ isset($oModel) ? 'Редактирование статической страницы' : 'Создание статической страницы' }}
		</h6>
		<div class="card-body">
			<form method="post" class="form-horizontal" action="{{route('admin_static_page_store')}}" enctype="multipart/form-data">
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
						<label class="form-label">URL <small>(оставьте пустым для автоматической генерации)</small></label>
						<input type="text" name="slug" class="form-control" value="{{ isset($oModel)?$oModel->slug:old('slug') }}">
						<div class="invalid-feedback">
							{{ $errors->first('slug') }}
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label class="form-label">Seo_Title</label>
						<input type="text" name="seo_title" class="form-control{{ $errors->has("seo_title") ? " is-invalid" : "" }}" value="{{ old("seo_title",false)?old("seo_title"):(isset($oModel)?$oModel->seo_title:'') }}">
						<span class="invalid-feedback">
                        <strong>{{ $errors->first("seo_title") }}</strong>
                    </span>
					</div>
					<div class="form-group col-md-6">
						<label class="form-label">Seo_Keywords</label>
						<input type="text" name="seo_keywords" class="form-control{{ $errors->has("seo_keywords") ? " is-invalid" : "" }}" value="{{ old("seo_keywords",false)?old("seo_keywords"):(isset($oModel)?$oModel->seo_keywords:'') }}">
						<span class="invalid-feedback">
                        <strong>{{ $errors->first("seo_keywords") }}</strong>
                    </span>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Seo_Description</label>
					<textarea name="seo_description" class="form-control{{ $errors->has("seo_description") ? " is-invalid" : "" }}">{{ old("seo_description",false)?old("seo_description"):(isset($oModel)?$oModel->seo_description:'') }}</textarea>
					<span class="invalid-feedback">
                        <strong>{{ $errors->first("seo_description") }}</strong>
                    </span>
				</div>
				{{--<div class="form-group">
					<label class="form-label">Текст страницы</label>
					<textarea name="text" id="article" class="form-control {{ $errors->has('text') ? ' id-invalid' : '' }}">{{ isset($oModel)?$oModel->text:old('text') }}</textarea>
					<span class="invalid-feedback">
						<strong>{{ $errors->first('text') }}</strong>
					</span>
				</div>--}}

				<div class="form-group text-right">
                    @if(isset($oModel))
                        <a href="{{route('static_page_show',['oModel'=>$oModel->slug,'edit'=>1])}}" class="btn btn-primary">Редактировать содержимое</a>
                    @endif
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>

@endsection

@section('scripts')
	<script src="{{ asset('/admin/libs/tinymce/tinymce.min.js') }}"></script>


	<script>
        //tinymce.init({...tiny_mce_conf});
	</script>
@endsection
