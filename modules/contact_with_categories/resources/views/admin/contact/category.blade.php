@extends('admin.layout')

@section('page_name', 'Категории контактов')

@section('content')
	<div class="ibox">
		<div class="ibox-title">
			<h5>Создать категорию</h5>
		</div>
		<div class="ibox-content">
			@if(session('success'))
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					{{ session('success') }}
				</div>
			@endif
			<div class="project-list">
				<form id="edit_form" method="post" class="form-horizontal" action="{{ route('admin_contact_category_store') }}" >
					{{ csrf_field() }}
					<div class="form-group">
						<label class="col-lg-1 control-label">Название</label>
						<div class="col-lg-11">
							<input type="text" name="name" class="form-control">
						</div>
						<input type="hidden" name="id" class="form-control">
					</div>
					<div class="form-group">
						<div class="col-lg-12 text-right">
							<button class="ladda-button btn btn-w-m btn-primary" data-style="expand-right" type="submit">Добавить</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="ibox">
		<div class="ibox-title">
			<h5>Категории для контактов</h5>
		</div>
		<div class="ibox-content">
			<div class="project-list">

				<table class="table table-hover">
					<tbody>
					@foreach($oModels as $oModel)
						<tr>
							<td class="project-status hidden-xs">
								@if($oModel->published)
									<span class="label label-primary">Active</span>
								@else
									<span class="label label-warning">Hidden</span>
								@endif
							</td>
							{{--<td class="project-status">
								<span class="label label-success">{{ $oModel->usage_count }}</span>
							</td>--}}
							<td class="project-title">
								<a href="/">{{ $oModel->name }}</a>
							</td>
							<td class="project-actions">
								<a href="#" class="m-b-xs btn btn-primary btn-sm edit_category" title="edit" data-id="{{ $oModel->id }}"><i class="fa fa-pencil"></i>  </a>
								<a href="{{ route('admin_contact_category_public', ['oModel' => $oModel]) }}" class="m-b-xs btn btn-warning btn-sm" title="hide"><i class="fa fa-eye"></i>
								</a>
								<form class="inline" method="post" action="{{ route('admin_contact_category_delete', ['oModel'=>$oModel]) }}">
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