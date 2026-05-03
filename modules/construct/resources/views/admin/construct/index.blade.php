@@extends("admin.layout")

@@section("page_name", "{{ $sModelName }}s")

@@section("content")

    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-2">
        <div>{{ $sModelName }}</div>
        <a href="{{ '{{' }} route("admin_{{ strtolower($sModelName) }}_create") }}" type="button" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp; Создать запись</a>
    </h4>
    <div class="card">
        <h6 class="card-header">
            Список
        </h6>
        <div class="card-datatable table-responsive">
            @@if(session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    @{{ session('success') }}
                </div>
            @@endif
            <table class="datatables table table-striped table-bordered" data-sort="0">
                <thead>
                <tr>
@if(!empty($oRequest->input('index_fields')))
{{----}}@foreach($oRequest->input('index_fields') as $key=>$sIndexField)
                    <th>{{ $sIndexField }}</th>
{{----}}@endforeach
@if($oRequest->input('published_field',false))
                    <th>{{ $oRequest->input('published_field') }}</th>
@endif
                    <th>Управление</th>
                </tr>
@endif
                </thead>
                <tbody>
                @@foreach($oModels as $key=>$oModel)
                    <tr class="@{{ $key % 2 == 0 ? 'odd gradeX' : 'even gradeC'}})">
@if(!empty($oRequest->input('index_fields')))
{{----}}@foreach($oRequest->input('index_fields') as $sIndexField)
{{--    --}}@if($sIndexField=='name' || $sIndexField=='title')
                        <td>
{{--        --}}@if($sImageType=='only-main' || $sImageType=='full')
                            <img src="@{{ $oModel->image?$oModel->image->path:"" }}" alt="" width="32">
{{--        --}}@endif
                            {{ '{{' }} $oModel->{{ $sIndexField }} }}
                        </td>
{{--    --}}@else
                        <td>{{ '{{' }} $oModel->{{ $sIndexField }} }}</td>
{{--    --}}@endif
{{----}}@endforeach
{{----}}@if($oRequest->input('published_field',false))
                        <td>
                        @@if($oModel->{{ $oRequest->input('published_field') }})
                            <span class="badge badge-outline-success">Active</span>
                        @@else
                            <span class="badge badge-outline-warning">Hidden</span>
                        @@endif
                        </td>
{{----}}@endif

                        <td class="center">
                            <a href="{{ '{{' }} route("admin_{{ strtolower($sModelName) }}_edit",["oModel"=>$oModel]) }}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Edit"><i class="ion ion-md-create"></i></a>
@if($oRequest->input('published_field',false))
                            <a href="{{ '{{' }} route("admin_{{ strtolower($sModelName) }}_public",["oModel"=>$oModel]) }}" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip" title="" data-original-title="Ban"><i class="ion @{{$oModel->published ? 'ion-md-eye-off' : 'ion-md-eye'}}"></i></a>
@endif
                            <a type="submit" class="btn btn-default btn-xs icon-btn md-btn-flat user-tooltip delete" title="delete"><i class="ion ion-md-trash"></i></a>
                            <form method="post" action="{{ '{{' }} route("admin_{{ strtolower($sModelName) }}_delete",["oModel"=>$oModel]) }}" style="display: none">
                                @{{ csrf_field() }}
                                @{{ method_field("DELETE") }}
                            </form>
                        </td>
@endif
                    </tr>
                @@endforeach
                </tbody>
            </table>
        </div>
    </div>
@@endsection