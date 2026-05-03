@extends('admin.layout')

@section('page_name', 'Статические страницы')

@section('content')

	<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-2">
		<div>Страницы</div>
		<a href="{{route('admin_static_page_create')}}" type="button" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp; Создать страницу</a>
	</h4>
	<div class="card">
		<h6 class="card-header">
			Список страниц
		</h6>
		<div class="card-datatable table-responsive">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
			<table class="datatabless table table-striped table-bordered" id="main_table">
				<thead>
				<tr>
					<th>Название</th>
					<th>URL</th>
					<th>Статус</th>
					<th>Управление</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>

@endsection

@section("scripts")
    <script>
        $('#main_table').dataTable( {
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: "POST",
                data: function ( d ) {
                    d._token = "{{csrf_token()}}";
                }
            },
            columns: [
                { data: "name" },
                {
                    data: "slug",
                    render: function( data, type, row, meta ){
                        let link = '{{ route("static_page_show",["oModel"=>'__i__']) }}'.replace(/__i__/g,data);
                        return `<a href="${link}" target="_blank">${link}</a>`;
                    }
                },
                {
                    data: "published",
                    render: function( data, type, row, meta ){
                        return data==1?'<span class="badge badge-outline-success">Active</span>':'<span class="badge badge-outline-warning">Hidden</span>';
                    }
                },
                {
                    data: function ( row, type, set, meta) {
                        let edit_link = '{{ route("admin_static_page_edit",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let public_link = '{{ route("admin_static_page_public",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let delete_link = '{{ route("admin_static_page_delete",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        return `<a href="${edit_link}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Edit"><i class="ion ion-md-create"></i></a>
                            <a href="${public_link}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Ban"><i class="ion ${row.published ? 'ion-md-eye-off' : 'ion-md-eye'}"></i></a>
                            <a type="submit" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip delete" title="delete"><i class="ion ion-md-trash"></i></a>
                            <form method="post" action="${delete_link}" style="display: none">
                                {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        </form>`;
                    },
                    orderable:false,
                },
            ],
            order:[[0, 'asc']],
            ordering: true,
        } );
    </script>
@endsection
