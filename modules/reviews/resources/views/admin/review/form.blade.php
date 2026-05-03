@extends('admin.layout')

@section('page_name', isset($oModel)?'Редактирование отзыва':'Создание отзыва')

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
					<h5>Заполните поля <small>для добавления нового отзыва.</small></h5>
				</div>
				<div class="ibox-content">
					<form method="post" class="form-horizontal" action="{{route('admin_review_store')}}" enctype="multipart/form-data" >
						{{ csrf_field() }}
						@if(isset($oModel))
							<input type="hidden" name="id" value="{{ $oModel->id }}">
						@endif
						<div class="form-group {{ $errors->has('categories') ? ' has-error' : '' }}">
							<label class="col-lg-1  control-label">Пользователь-автор</label>
							<small>Если не выбран, укажите ФИО и Аватар в полях ниже</small>
							<div class="col-lg-11">
								<select name="user_id" data-placeholder="Выберите категории" class="chosen-select" tabindex="4">
									<option value="{{ NULL }}">Ложный отзыв</option>
									@foreach($oUsers as $oUser)
										<option value="{{ $oUser->id }}" {{ (isset($oModel) && $oModel->user_id==$oUser->id) ? 'selected':($oUser->id==old('user_id')?'selected':'') }}>{{ $oUser->name }} {{ $oUser->surname }}</option>
									@endforeach
								</select>
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('categories') }}</strong>
                            </span>
						</div>

						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">ФИО автора</label>
							<small>Не будет учтено, если указан пользователь-автор</small>
							<div class="col-lg-11">
								<input type="text" name="name" class="form-control" value="{{ isset($oModel)?$oModel->name:old('name') }}">
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
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
						<div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
							<label class="col-lg-1 control-label">Текст отзыва</label>
							<div class="col-lg-11">
								<textarea name="text" class="summernote form-control">{{ isset($oModel)?$oModel->text:old('text') }}</textarea>
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('text') }}</strong>
                            </span>
						</div>



						<div class="hr-line-dashed"></div>
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title  back-change">
									<h5>Аватар</h5>
								</div>
								<div class="ibox-content">
									<p>
										Выберите область для отображения в превью
									</p>
									<div class="row">
										<div class="col-md-6">
											<div class="image-crop">
												<img src="{{ (isset($oModel) && !empty($oModel->image))?$oModel->image->path:'/admin/img/default.jpg' }}">
											</div>
											<div class="m-t-sm btn-group">
												<button class="btn btn-white" id="zoomIn" type="button">
													<i class="fa fa-search-minus"></i> Zoom In
												</button>
												<button class="btn btn-white" id="zoomOut" type="button">
													<i class="fa fa-search-plus"></i> Zoom Out
												</button>
											</div>
										</div>
										<div class="col-md-6">
											<div class="img-preview img-preview-4-3"></div>
											<h4>Загрузка изображения</h4>
											<p>
												Удобный редактор изобраений позволяет загружать любые картинки с различными размерами
												и выбрать нужную область для отображения в превью
											</p>
											<div class="btn-group">
												<input type="hidden" name="crop">
												<label title="Upload image file" for="inputImage" class="btn btn-primary">
													<input type="file" accept="image/*" name="preview" id="inputImage" class="hide">
													Загрузить изображение
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<span class="help-block">
                                <strong>{{ $errors->first('preview') }}</strong>
                            </span>
						</div>

						{{--<div class="row">
							<div class="col-md-12">
								<div class="ibox-title  back-change">
									<h5>Изображения продукта</h5>
								</div>

								<div class="ibox-content">
									<div class="add-image-box">
										@if(isset($oModel))
											@foreach($oModel->images as $oImage)
												@if($oImage->is_main!=1)
													<div class="add-image-box__el active" data-nowImgId="{{ $oImage->id }}">
														<label class="add-image-inputLabel">
															<input type="file" class="add-image-images" name="images[{{ $oImage->id }}]">
														</label>

														<img src="{{ $oImage->path }}" class="add-image-images__target-{{ $oImage->id }}" alt="">

														<div class="remove-img">Удалить</div>
													</div>
												@endif
											@endforeach
										@endif
										<div class="add-image-box__el">
											<label class="add-image-inputLabel">
												<input type="file" class="add-image-images" name="images[]">
											</label>

											<img src="" class="add-image-images__target-1" alt="">

											<div class="remove-img">Удалить</div>
										</div>
									</div>
								</div>
								<span class="help-block">
                                	<strong>{{ $errors->first('images') }}</strong>
                            	</span>
							</div>
						</div>--}}

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
	<link href="{{ asset('/admin/css/plugins/cropper/cropper.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/admin/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

@endsection

@section('scripts')
	<script src="{{ asset('/admin/js/plugins/cropper/cropper.min.js') }}"></script>
	<script src="{{ asset('/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>

	<script src="{{ asset('/admin/js/crop.js') }}"></script>

	<script>

		$('.chosen-select').chosen({width: "100%"});

		$('.summernote').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['picture','link','video','table','hr']],
                ['misk',['fullscreen','codeview','undo','redo','help']]
            ],
            lang: "ru-RU"
		});

	</script>
@endsection
