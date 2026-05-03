<li class="sidenav-item{{ Route::currentRouteName()=="admin_dialog_" ? " active" : false }}">
	<a href="{{ route("admin_dialog_") }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-cog"></i>
		<div>Dialog</div>
	</a>
</li>
<li class="sidenav-item{{ Route::currentRouteName()=="admin_message_" ? " active" : false }}">
	<a href="{{ route("admin_message_") }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-cog"></i>
		<div>Message</div>
	</a>
</li>