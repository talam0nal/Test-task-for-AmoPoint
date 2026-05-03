@extends('admin.layout')

@section('page_name', 'Статьи')

@section('content')
	<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-2">
		<div>Портфолио</div>
		<a href="{{route('admin_portfolio_create')}}" type="button" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp; Добавить кейс</a>
	</h4>
	<div class="card">
		<h6 class="card-header">
			Список
		</h6>
		<div class="card-datatable table-responsive">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
			<table class="datatables table table-striped table-bordered" data-sort="0">
				<thead>
				<tr>
					<th>ID</th>
					<th>Название</th>
					<th>Статус</th>
					<th>Дата публикации</th>
					<th>Управление</th>
				</tr>
				</thead>
				<tbody>
				@foreach($oModels as $key=>$oModel)
					<tr class="{{ $key % 2 == 0 ? 'odd gradeX' : 'even gradeC'}})">
						<td>{{ $oModel->id }}</td>
						<td><img src="{{ $oModel->image?$oModel->image->path:'' }}" alt="" width="32"> {{ $oModel->name }}</td>
						<td>
							@if($oModel->published)
								<span class="badge badge-outline-success">Active</span>
							@else
								<span class="badge badge-outline-warning">Hidden</span>
							@endif
						</td>
						<td>{{ $oModel->created_at }}</td>
						<td class="center">
							<a href="{{ route('admin_portfolio_edit',['oModel'=>$oModel]) }}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Edit"><i class="ion ion-md-create"></i></a>
							<a href="{{ route('admin_portfolio_public',['oModel'=>$oModel]) }}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Ban"><i class="ion {{$oModel->published ? 'ion-md-eye-off' : 'ion-md-eye'}}"></i></a>
							<a type="submit" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip delete" title="delete"><i class="ion ion-md-trash"></i></a>
							<form method="post" action="{{ route('admin_portfolio_delete',['oModel'=>$oModel]) }}" style="display: none">
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
@endsection