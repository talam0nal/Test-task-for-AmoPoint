@extends('admin.layout')

@section('page_name', 'Контакт')

@section('content')
	<div class="ibox">
		<div class="ibox-title">
			<h5>Все портфолио</h5>
			<div class="ibox-tools">
				<a href="{{route('admin_contact_create')}}" class="btn btn-primary btn-sm">Добавить контакт</a>
			</div>
		</div>
		<div class="ibox-content">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
			<div class="row m-b-sm m-t-sm">
				<div class="col-md-12">
					<form method="get" action="">
						<div class="input-group">
							<input type="text" name="sText" placeholder="Поиск" class="input-sm form-control" value="{{ request('sText') }}">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-sm btn-primary"> Go!</button> </span></div>
					</form>
				</div>
			</div>

			<div class="project-list">

				<table class="table table-hover">
					<tbody>
					@foreach($oModels as $oModel)
						<tr>
							{{--<td class="hidden-xs project-preview">
								<img src="{{ $oModel->image?$oModel->image->path:'' }}" alt="">
							</td>--}}
							<td class="project-status hidden-xs">
								@if($oModel->published)
									<span class="label label-primary">Active</span>
								@else
									<span class="label label-warning">hidden</span>
								@endif
							</td>
							<td class="project-title">
								<a href="#">{{ $oModel->name }}</a>
							</td>
							<td class="project-status hidden-xs">
								{{ $oModel->value }}
							</td>
							<td class="project-stat hidden-sm hidden-xs">
								очередность: {{ $oModel->order }}<br>
							</td>
							<td class="project-stat hidden-sm hidden-xs">
								Категория: {{ $oModel->category->name }}<br>
							</td>
							<td class="project-actions">
								<a href="{{ route('admin_contact_edit',['oModel'=>$oModel]) }}" class="m-b-xs btn btn-primary btn-sm" title="edit"><i class="fa fa-pencil"></i>  </a>
								<a href="{{ route('admin_contact_public',['oModel'=>$oModel]) }}" class="m-b-xs btn btn-warning btn-sm" title="hide"><i class="fa fa-eye"></i>
								</a>
								<form class="inline" method="post" action="{{ route('admin_contact_delete',['oModel'=>$oModel]) }}">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
									<button type="submit" class="m-b-xs btn btn-danger btn-sm" title="delete"><i class="fa fa-trash-o"></i>  </button>
								</form>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				{{ $oModels->links() }}
			</div>
		</div>
	</div>

@endsection