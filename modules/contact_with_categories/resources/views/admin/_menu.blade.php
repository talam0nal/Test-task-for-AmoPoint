<li class="sidenav-item{{ in_array(Route::currentRouteName(),['admin_contact_category_','admin_contact_']) ? ' active open' : false }}">
	<a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-speedometer"></i>
		<div>Контакты</div>
	</a>
	<ul class="sidenav-menu">
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_contact_category_' ? ' active' : false }}">
			<a href="{{ route('admin_contact_category_') }}" class="sidenav-link">
				<div>Категории</div>
			</a>
		</li>
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_contact_' ? ' active' : false }}">
			<a href="{{ route('admin_contact_') }}" class="sidenav-link">
				<div>Список</div>
			</a>
		</li>
	</ul>
</li>