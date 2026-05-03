@extends('admin.layout')

@section('page_name', 'Пользователи')

@section('content')
	<div class="card">
		<h6 class="card-header">
			Список пользователей
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
					<th>id</th>
					<th>Статус</th>
					<th>Имя</th>
					<th>Email</th>
					<th>Роль</th>
					<th>Дата создания</th>
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
                { data: "id" },
                {
                    data: "active",
                    render: function( data, type, row, meta ){
                        return data==1?'<span class="badge badge-outline-success">Active</span>':'<span class="badge badge-outline-danger">Banned</span>';
                    }
                },
                {
                    data: "surname",
                    render: function( data, type, row, meta ){
                        return (row.main_image?`<img style="height: 50px" src="${row.main_image.path}" alt="">`:'')+row.name+' '+row.surname
                    }
                },
                { data: "email" },
                {
                    data: "is_admin",
                    render: function( data, type, row, meta ){
                        return data==1?'Администратор':'Пользователь';
                    }
                },
                {
                    data: "created_at",
                    render: function( data, type, row, meta ){
                        let date = new Date(data);
                        return date.toLocaleDateString()+' '+date.toLocaleTimeString();
                    }
                },
                {
                    data: function ( row, type, set, meta) {
                        let edit_link = '{{ route("admin_user_edit",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let public_link = '{{ route("admin_user_public",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let delete_link = '{{ route("admin_user_delete",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        return `<a href="${edit_link}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Edit"><i class="ion ion-md-create"></i></a>
                            <a href="${public_link}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Ban"><i class="ion ${row.active ? 'ion-md-eye-off' : 'ion-md-eye'}"></i></a>
                            <a type="submit" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip delete" title="delete"><i class="ion ion-md-trash"></i></a>
                            <form method="post" action="${delete_link}" style="display: none">
                                {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        </form>`;
                    },
                    orderable:false,
                },
            ],
            order:[[2, 'asc']],
            ordering: true,
        } );
    </script>
@endsection
