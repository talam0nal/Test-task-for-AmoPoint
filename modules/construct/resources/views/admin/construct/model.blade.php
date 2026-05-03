<?php echo '<?php'; ?>

/**
 * Created by Velgir
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
@if($oRequest->has('slug'))
    use App\Traits\SlugName;
@endif
@if($oRequest->has('order'))
    use App\Traits\OrderSort;
@endif
class {{ str_replace('_','',\Illuminate\Support\Str::title($oRequest->input('model_name'))) }} extends Model
{
@if($oRequest->has('order'))
    use OrderSort;

    @if($oRequest->input('order')!=='order')
        public function getOrderField()
        {
        return "{{ $oRequest->input('order') }}";
        }
    @endif
@endif
@if($oRequest->has('slug'))
    use SlugName;

    @if($oRequest->input('slug')!=='slug')
    public function getSlugField()
    {
        return "{{ $oRequest->input('slug') }}";
    }
    @endif
@endif
protected $table = "{{  $aTableInfo['name'] }}";
@if(!in_array('created_at',$aTableInfo['fields']) && !in_array('updated_at',$aTableInfo['fields']))

    public $timestamps = false;
@endif
@if($oRequest->has('slug')){{-- route_name --}}

    public function getRouteKeyName()
    {
        return "{{ $oRequest->input('slug')/*route_name*/}}";
    }
@endif
@if(!empty($oRequest->input('fillable')))

    protected $fillable = [
        "{!! implode('", "',$oRequest->input('fillable')) !!}"
    ];
@endif
@if(!empty($oRequest->input('hidden')))

    protected $hidden = [
        "{!! implode('", "',$oRequest->input('hidden')) !!}"
    ];
@endif
@if(!empty($oRequest->input('addition_sizes')))
    public $image_sizes = [
        @foreach($oRequest->input('addition_sizes') as $aAdditionSize)
        '{{ $aAdditionSize['name'] }}' => [{{ $aAdditionSize['width'] }},{{ $aAdditionSize['height'] }}],
        @endforeach
    ];
@endif
@if($oRequest->input('image')=='only-main' || $oRequest->input('image')=='full')

    public function image()
    {
        return $this->morphOne(Image::class,"object")->where("is_main",1);
    }

    @if(!empty($oRequest->input('addition_sizes')))
public function getImage($sSizeName)
    {
        $oImage = $this->image;
        if($oImage)
        {
            $sBaseName = basename($oImage->path);
            return str_replace($sBaseName,$sSizeName.'_'.$sBaseName,$oImage->path);
        }
        else
            return '';
    }
    @endif
@endif
@if($oRequest->input('image')=='only-addition' || $oRequest->input('image')=='full')

    public function images()
    {
        return $this->morphMany(Image::class,"object");
    }

    @if(!empty($oRequest->input('addition_sizes')))
public function getImages($sSizeName)
    {
        $aImagePaths = [];
        $oImages = $this->images;
        foreach($oImages as $oImage)
        {
            $sBaseName = basename($oImage->path);
            $aImagePaths[] = str_replace($sBaseName,$sSizeName.'_'.$sBaseName,$oImage->path);
        }
        return $aImagePaths;
    }
    @endif
@endif
@if(!empty($oRequest->input('relation')))
    @foreach($oRequest->input('relation') as $aRelationData)

    public function {{ $aRelationData['name'] }}()
    {
        return $this->{{  $aRelationData['type'] }}({{ \Illuminate\Support\Str::title($aRelationData['class']) }}::class,
        @if(!empty($aRelationData['join_table']) && $aRelationData['type']=='belongsToMany')
        "{{ $aRelationData['join_table'] }}",
        @endif
        "{{ $aRelationData['foreign_key'] }}", "{{ $aRelationData['other_key'] }}");
    }
    @endforeach
@endif

@if(!empty($aBelongsToMany))
    @foreach($aBelongsToMany as $aRelationship)

    public function edit{{  \Illuminate\Support\Str::title($aRelationship['name']) }}($aNewCatId)
    {
        $relation = $this->{{ $aRelationship['name'] }}();
        $aOldCatId = $relation->pluck("{{ $aRelationship['table_name'] }}.id")->toArray();
        $ids_to_delete = array_diff($aOldCatId,$aNewCatId);
        $ids_to_add = array_diff($aNewCatId,$aOldCatId);
        $relation->detach($ids_to_delete);
        $relation->attach($ids_to_add);
    }
    @endforeach
@endif

}