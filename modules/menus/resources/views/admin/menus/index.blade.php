@extends('admin.layout')

@section('page_name', 'Пункты меню')

@section('content')

	<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-2">
		<div>Пункты меню</div>
		<a href="{{route('admin_menus_create')}}" type="button" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp; Добавить пункт</a>
	</h4>
	<div class="card">
		<h6 class="card-header">
			Все пункты
		</h6>
		<div class="card-datatable table-responsive">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
                <div class="form-group col-md-4">
                    <label class="form-label">Тип меню</label>
                    <select data-placeholder="Выберите Тип меню" class="select2 custom-select type-select" tabindex="4" data-allow-clear="true" multiple>
                        @foreach($types as $key=>$oType)
                            <option value="{{ $key }}">{{ $oType }}</option>
                        @endforeach
                    </select>
                </div>
			<table class="datatabless table table-striped table-bordered" id="main_table">
				<thead>
				<tr>
					<th>Статус</th>
					<th>Название</th>
					<th>Очередность</th>
					<th>URL</th>
					<th>Управление</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection

@section('css')

@endsection

@section("scripts")
    <script>
        const menu_types = JSON.parse(`{!! json_encode($types) !!}`);
        var table = $('#main_table').dataTable( {
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: "POST",
                data: function ( d ) {
                    d._token = "{{csrf_token()}}";
                    d.type = $('.type-select').val();
                }
            },
            columns: [
                {
                    data: "active",
                    render: function( data, type, row, meta ){
                        return data==1?'<span class="badge badge-outline-success">Active</span>':'<span class="badge badge-outline-warning">Hidden</span>';
                    }
                },
                {
                    data: "name",
                    render: function( data, type, row, meta ){//$types
                        return data+`<br/><small>Тип меню ${menu_types[row.type] || row.type}</small>`;
                    }
                },
                { data: "order" },
                {
                    data: "link",
                    render: function( data, type, row, meta ){
                        return `<a href="${data}" target="_blank">${data}</a>`;
                    }
                },
                {
                    data: function ( row, type, set, meta) {
                        let edit_link = '{{ route("admin_menus_edit",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let public_link = '{{ route("admin_menus_public",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
                        let delete_link = '{{ route("admin_menus_delete",["oModel"=>'__i__']) }}'.replace(/__i__/g,row.id);
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
        $('.type-select').on('change', function() {
            table.fnDraw();
        } );
    </script>
@endsection
