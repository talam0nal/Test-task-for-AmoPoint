@extends('admin.layout')

@section('page_name', 'Категории блога')

@section('content')
	<div class="card mb-4">
		<h6 class="card-header">
			Создать категорию
		</h6>
		<div class="card-body">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
			<form id="edit_form" method="post" class="form-horizontal" action="{{ route('admin_portfolio_category_store') }}" >
				{{ csrf_field() }}
				<div class="form-group">
					<input type="text" name="name" class="form-control" placeholder="Введите название новой категории">
				</div>
				<div class="form-group text-right mb-0">
					<button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Создать</button>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
		<h6 class="card-header">
			Список категорий
		</h6>
		<div class="card-datatable table-responsive">
			<table class="datatables table table-striped table-bordered" data-sort="0">
				<thead>
				<tr>
					<th>ID</th>
					<th>Название</th>
					<th>Статус</th>
					<th>Управление</th>
				</tr>
				</thead>
				<tbody>
				@foreach($oModels as $key=>$oModel)
					<tr class="{{ $key % 2 == 0 ? 'odd gradeX' : 'even gradeC'}})">
						<td>{{ $oModel->id }}</td>
						<td>{{ $oModel->name }}</td>
						<td>
							@if($oModel->published)
								<span class="badge badge-outline-success">Active</span>
							@else
								<span class="badge badge-outline-warning">Hidden</span>
							@endif
						</td>
						<td class="center">
							<a href="#" onclick="edit('{{ $oModel->id }}', '{{ $oModel->name }}'); return false;" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Edit"><i class="ion ion-md-create"></i></a>
							<a href="{{ route('admin_portfolio_category_public', ['oModel' => $oModel]) }}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Ban"><i class="ion {{$oModel->published ? 'ion-md-eye-off' : 'ion-md-eye'}}"></i></a>
							<a type="submit" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip delete" title="delete"><i class="ion ion-md-trash"></i></a>
							<form method="post" action="{{ route('admin_portfolio_category_delete', ['oModel'=>$oModel]) }}" style="display: none">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
							</form>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<div class="modal fade" id="modal-edit">
		<div class="modal-dialog">
			<form method="post" class="modal-content" action="{{ route('admin_portfolio_category_store') }}" >
				{{ csrf_field() }}
				<div class="modal-header">
					<h5 class="modal-title">
						Редактирование
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body">
					<div class="form-row">
						<div class="form-group col">
							<label class="form-label">Название категории</label>
							<input type="text" name="name" class="form-control" value="">
							<input type="hidden" name="id" class="form-control edit-id" value="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary">Редактировать</button>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('css')
	<link rel="stylesheet" href="{{ asset('/admin/libs/datatables/datatables.css') }}">
	<link rel="stylesheet" href="{{ asset('/admin/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('scripts')
	<script src="{{ asset('/admin/libs/datatables/datatables.js') }}"></script>
	<script src="{{ asset('/admin/libs/sweetalert2/sweetalert2.js') }}"></script>

	<script>
        function edit(ID, name){
            $('#modal-edit').find('[name=name]').val(name);
            $('#modal-edit').find('[name=id]').val(ID);
            $('#modal-edit').modal();
        }

        $(function() {

            //$('.datatables').dataTable().show();

            $('.delete').click(function() {
                var user = $(this);
                Swal.fire({
                    title: 'Вы уверены?',
                    text: 'Удалить категорию?',
                    icon: 'warning',
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then(function(result) {
                    if (result.value) {
                        user.next('form').submit();
                        Swal.fire('Deleted!', 'Категория удалена.', 'success');
                        setTimeout(function(){
                            window.location.reload();
                        }, 1000);
                    } else {
                        Swal.fire('Cancelled', 'Действие отменено', 'error');
                    }
                });
            });
        });
	</script>
@endsection
