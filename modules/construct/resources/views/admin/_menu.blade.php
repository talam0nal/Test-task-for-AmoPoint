{{--Constructor--}}
@if(env('APP_DEBUG'))
	<li class="sidenav-divider mb-1"></li>
	<li class="sidenav-header small font-weight-semibold">Development</li>
	<li class="sidenav-item {{ (Route::currentRouteName()=='admin_modules_') ? 'active' : false }}">
		<a href="{{ route('admin_modules_') }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-code-download"></i>
			<div>Модули</div>
		</a>
	</li>
	<li class="sidenav-item {{ (in_array(Route::currentRouteName(),['admin_construct_create_module_1','admin_construct_create_module_2','admin_construct_create_module_3'])) ? 'active' : false }}">
		<a href="{{ route('admin_construct_create_module_1') }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-code"></i>
			<div>Создание модуля</div>
		</a>
	</li>
	<li class="sidenav-item {{ (Route::currentRouteName()=='admin_construct_delete_module') ? 'active' : false }}">
		<a href="{{ route('admin_construct_delete_module') }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-trash"></i>
			<div>Удалить конструктор</div>
		</a>
	</li>
@endif
{{--/Constructor--}}

