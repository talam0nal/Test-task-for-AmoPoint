<li class="sidenav-item{{ in_array(Route::currentRouteName(),['admin_portfolio_category_','admin_portfolios_']) ? ' active open' : false }}">
	<a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-speedometer"></i>
		<div>Портфолио</div>
	</a>
	<ul class="sidenav-menu">
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_portfolio_category_' ? ' active' : false }}">
			<a href="{{ route('admin_portfolio_category_') }}" class="sidenav-link">
				<div>Категории</div>
			</a>
		</li>
		<li class="sidenav-item{{ Route::currentRouteName()=='admin_portfolio_' ? ' active' : false }}">
			<a href="{{ route('admin_portfolio_') }}" class="sidenav-link">
				<div>Список</div>
			</a>
		</li>
	</ul>
</li>