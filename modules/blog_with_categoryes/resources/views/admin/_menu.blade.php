<li class="sidenav-item{{ (in_array(Route::currentRouteName(),['admin_blog_','admin_blog_category_']))? ' active open' : false }}">
	<a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-paper"></i>
		<div>Новости</div>
	</a>
	<ul class="sidenav-menu">
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_blog_' ? ' active' : false }}">
			<a href="{{ route('admin_blog_') }}" class="sidenav-link">
				<div>Список новостей</div>
			</a>
		</li>
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_blog_category_' ? ' active' : false }}">
			<a href="{{ route('admin_blog_category_') }}" class="sidenav-link">
				<div>Категории</div>
			</a>
		</li>
	</ul>
</li>