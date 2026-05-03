<?php echo '<?php'; ?>

/**
 * Created by Velgir
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

@php
$aRelationClasses = [];
@endphp
@foreach($aRelationships as $aRelationship)
{{----}}@if(!in_array($aRelationship['class'],$aRelationClasses))
{{--    --}}use App\Models\{{ \Illuminate\Support\Str::title($aRelationship['class']) }};
{{--    --}}@php    $aRelationClasses[] = $aRelationship['class'];  @endphp
{{----}}@endif
@endforeach
@if($sImageType!='no-image')
{{----}}use App\Models\Image;
@endif
use App\Models\{{ $sModelName }};

class {{ \Illuminate\Support\Str::title($oRequest->input('controller_name')) }}Controller extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {
        if(!$oRequest->input("sText"))
            $oModels = {{ $sModelName }}::orderBy({!! ((isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))?'"'.$aModelInfo['order_field'].'"':'"id", "desc"') !!})
@if(!empty($sWithBlock))
                {!! $sWithBlock !!}
@endif
                ->get();
@if(!empty($oRequest->input("search_fields")))
        else
            $oModels = {{  $sModelName }}::
{{--    --}}@if(!empty($oRequest->input('search_fields')))
{{--        --}}@foreach($oRequest->input('search_fields') as $iKey=>$sSerchField)
                {!! ($iKey==0)?'where':'->orWhere' !!}("{{ $sSerchField }}","like","%".$oRequest->input("sText")."%")
{{--        --}}@endforeach
{{--    --}}@endif
{{--    --}}@if(!empty($sWithBlock))
                {!! $sWithBlock !!}
{{--    --}}@endif
            ->orderBy({!! ((isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))?'"'.$aModelInfo['order_field'].'"':'"id", "desc"') !!})->get();
@endif
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view("admin.{{ strtolower($oRequest->input('controller_name')) }}.index",[
                    "oModels" => $oModels,
            ]);
    }

    public function AdminCreate()
    {
@php
        $sParams = '';
        $aSettedRelations = [];
@endphp
@foreach($aRelationships as $aRelationship)
{{----}}@if($aRelationship['type']=='belongsTo' OR $aRelationship['type']=='belongsToMany')
{{--    --}}@if(!in_array($aRelationship['class'],$aSettedRelations))
{{----}}        $o{{ \Illuminate\Support\Str::title($aRelationship['class']) }}s = {{ \Illuminate\Support\Str::title($aRelationship['class']) }}::all();
@php
                $sParams.='"o'.\Illuminate\Support\Str::title($aRelationship['class']).'s"=>$o'.\Illuminate\Support\Str::title($aRelationship['class']).'s,';
                $aSettedRelations[] = $aRelationship['class'];
@endphp
{{--    --}}@endif
{{--    --}}@if($aRelationship['type']=='belongsToMany')
{{--        --}}@php $sParams.='"aExist'.\Illuminate\Support\Str::title($aRelationship['class']).'Id"=>[],'; @endphp
{{--    --}}@endif
{{----}}@endif
@endforeach
@if(isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))
        $iRecordCount={{ $sModelName }}::count()+1;
@php
            $sParams.='"iRecordCount"=>$iRecordCount,';
@endphp
@endif
        return view("admin.{{ strtolower($oRequest->input('controller_name')) }}.form"
@if(!empty($sParams))
           ,[{!! $sParams !!}]
@endif
        );
    }

    public function AdminEdit({{ $sModelName }} $oModel)
    {
@php
        $sParams = '"oModel"=>$oModel,';
        $aSettedRelations = [];
@endphp
@foreach($aRelationships as $iKey=>$aRelationship)
{{----}}@if($aRelationship['type']=='belongsTo' OR $aRelationship['type']=='belongsToMany')
{{--    --}}@php
                $oRelationPathModel = '\App\Models\\'.\Illuminate\Support\Str::title($aRelationship['class']);
                $oRelationModel = new $oRelationPathModel;
                $sRelationTable = $oRelationModel->getTable();
                $aRelationships[$iKey]['table_name'] = $sRelationTable;
            @endphp
{{--    --}}@if(!in_array($aRelationship['class'],$aSettedRelations))
{{----}}        $o{{ \Illuminate\Support\Str::title($aRelationship['class']) }}s = {{ \Illuminate\Support\Str::title($aRelationship['class']) }}::all();
{{--        --}}@php
                    $sParams.='"o'.\Illuminate\Support\Str::title($aRelationship['class']).'s"=>$o'.\Illuminate\Support\Str::title($aRelationship['class']).'s,';
                    $aSettedRelations[] = $aRelationship['class'];
                @endphp
{{--    --}}@endif
{{--    --}}@if($aRelationship['type']=='belongsToMany')
{{----}}        $aExist{{ \Illuminate\Support\Str::title($aRelationship['class']) }}Id = $oModel->{{ $aRelationship['name'] }}()->pluck("{{ $sRelationTable }}.id")->toArray();
{{--        --}}@php
                    $sParams.='"aExist'.\Illuminate\Support\Str::title($aRelationship['class']).'Id" => $aExist'.\Illuminate\Support\Str::title($aRelationship['class']).'Id,';
                @endphp
{{--    --}}@endif
{{----}}@endif
@endforeach
        return view("admin.{{ strtolower($oRequest->input('controller_name')) }}.form"
@if(!empty($sParams))
            ,[{!! $sParams !!}]
@endif
        );
    }

    public function AdminStore(Request $oRequest, {{ $sModelName }} $oModel)
    {
{{----}}@if(!empty($oRequest->input('validation_rules')))
        $this->validate($oRequest, [
{{----}}@foreach($oRequest->input('validation_rules') as $sField=>$aRules)
{{--    --}}@php $aRulesToField = []; @endphp
{{--    --}}@foreach($aRules['rules'] as $sRuleName)
{{--        --}}@if(isset($aRules['params'][$sRuleName]))
{{--            --}}@php $aRulesToField[] = $sRuleName.':'.$aRules['params'][$sRuleName]; @endphp
{{--        --}}@else
{{--            --}}@php $aRulesToField[] = $sRuleName; @endphp
{{--        --}}@endif
{{--    --}}@endforeach
            "{{ $sField }}"=>"{{ implode('|',$aRulesToField) }}",
{{----}}@endforeach
@if($sImageType=='only-main' || $sImageType=='full')
            "preview"	=> "image|mimes:jpeg,jpg,png",
@endif
@if($sImageType=='only-addition' || $sImageType=='full')
            "images"    => "array|nullable",
            "images.*"  => "image|mimes:jpeg,jpg,png",
@endif
@foreach($aRelationships as $aRelationship)
{{----}}@if($aRelationship['type']=='belongsTo')
            "{{ $aRelationship['foreign_key'] }}"=> "required|integer|exists:{{ $aRelationship['table_name'] }},{{ $aRelationship['other_key'] }}",
{{----}}@endif
{{----}}@if($aRelationship['type']=='belongsToMany')
            "{{ $aRelationship['name'] }}"=> "required|array",
            "{{ $aRelationship['name'] }}.*"=>"integer|exists:{{ $aRelationship['table_name'] }},id",
{{----}}@endif
@endforeach
        ]);
@endif
        if($oRequest->input("id")){
            $oModel = {{ $sModelName }}::find($oRequest->input("id"));
        }
@if($sImageType=='only-main' || $sImageType=='full')
        if(!$oRequest->input("id"))
        {
            $oImage = public_path("admin/img/default.jpg");
        }
        if($oRequest->hasFile("preview")){
            $oImage = $oRequest->file("preview");
        }
        $PreviewPath = false;
        if(isset($oImage))
            $PreviewPath = Image::cropImage($oImage,$oRequest->input("crop",NULL),"{{ strtolower($sModelName) }}", {{ $aModelInfo['image_size']['width'] }}, {{ $aModelInfo['image_size']['height'] }});
@endif
@php
        $aExceptInputs = [];
@endphp
@if(isset($aModelInfo['slug_field']) && !empty($aModelInfo['slug_field']) && isset($aModelInfo['slug_source']) && !empty($aModelInfo['slug_source']))
        if(!$oRequest->input("{{ $aModelInfo['slug_field'] }}",false))
            $oModel->{{ $aModelInfo['slug_field'] }} = $oModel->generateSlug($oRequest->input("{{ $aModelInfo['slug_source'] }}"));
        else
            $oModel->{{ $aModelInfo['slug_field'] }} = $oModel->adaptSlug($oRequest->input("{{ $aModelInfo['slug_field'] }}"));
{{----}}@php $aExceptInputs[] = $aModelInfo['slug_field']; @endphp
@endif
@if(isset($aModelInfo['order_field']) && !empty($aModelInfo['order_field']))
        $oModel->setOrder($oRequest->input("{{ $aModelInfo['order_field'] }}"));
{{----}}@php $aExceptInputs[] = $aModelInfo['order_field']; @endphp
@endif
@if(!empty($aExceptInputs))
        $oModel->fill($oRequest->except("{!! implode('", "',$aExceptInputs) !!}"));
@else
        $oModel->fill($oRequest->all());
@endif
        $oModel->save();
@if($sImageType=='only-main' || $sImageType=='full')
        if(!empty($PreviewPath))
        {
            if($oImage = $oModel->image)
                $oImage->remove();
            $oImage = $oModel->image()->create(["path"=>$PreviewPath,"is_main"=>1,"order"=>1]);
            $oImage->makeVariants();
        }
@endif
@foreach($aRelationships as $aRelationship)
{{----}}@if($aRelationship['type']=='belongsToMany')
{{----}}        $oModel->edit{{ \Illuminate\Support\Str::title($aRelationship['name']) }}($oRequest->input("{{  $aRelationship['name'] }}"));
{{----}}@endif
@endforeach
@if($sImageType=='only-addition' || $sImageType=='full')
        //Присваиваем загруженные изображения модели
        $aImagesID = $oRequest->input('images_id');
        if($aImagesID) {
            Image::massAssign($oRequest->input('images_id'), $oModel->id);
        } else {
            $aImagesID = [];
        }
        $aModelImages = [];
        foreach ($oModel->images as $oImage){
            if($oImage->is_main == false)
                $aModelImages[] = $oImage->id;
        }
        $aModelImages = array_merge($aModelImages, $aImagesID);
        $aModelImages = array_diff($aModelImages, array_diff_assoc($aModelImages, array_unique($aModelImages)));
        if(!empty($aModelImages))
            Image::massDelete(implode(',',$aModelImages));
@endif
        return redirect()->route("admin_{{ strtolower($sModelName) }}_")->with("success", "Запись успешно создана!");
    }

@if($oRequest->input('published_field',false))
    public function AdminPublic(Request $oRequest, {{ $sModelName }} $oModel)
    {
        $aResult = ["скрыта","опубликована"];
        $oModel->{{ $oRequest->input('published_field') }} = !$oModel->{{ $oRequest->input('published_field') }};
        $oModel->save();
        $sMessage = "Запись успешно ". $aResult[$oModel->{{ $oRequest->input('published_field') }}] ."!";
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with("success", $sMessage);
        else
            return json_encode(["status"=>"OK","message"=>$sMessage]);
    }
@endif

    public function AdminDelete({{ $sModelName }} $oModel)
    {
@if($sImageType=='only-main' || $sImageType=='full')
        $oModel->image->remove();
@endif
@if($sImageType=='only-addition' || $sImageType=='full')
        $aImagesId = $oModel->images()->pluck("images.id")->toArray();
        Image::massDelete(implode(",",$aImagesId));
@endif
@foreach($aRelationships as $aRelationship)
{{----}}@if($aRelationship['type']=='hasOne' || $aRelationship['type']=='hasMany')
{{----}}        $oModel->{{ $aRelationship['name'] }}()->delete();
{{----}}@endif
{{----}}@if($aRelationship['type']=='belongsToMany')
{{----}}        $oModel->{{ $aRelationship['name'] }}()->detach();
{{----}}@endif
@endforeach
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with("success", "Запись успешно удалена!");
        else
            return json_encode(["status"=>"OK","message"=>"Запись успешно удалена!"]);
    }
}