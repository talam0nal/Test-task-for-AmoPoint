<?php echo '<?php'; ?>

/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ $sClassName }}Table extends Migration
{
    public function up()
    {
        Schema::create('{{ strtolower($oRequest->input('table_name')) }}', function (Blueprint $table) {
            $table->increments("id");
    @foreach($oRequest->input('field') as $aField)
        $table->{{ $aField['type'] }}('{{ $aField['name'] }}'{{ !empty($aField['add_param_1'])?','.$aField['add_param_1']:'' }}{{ !empty($aField['add_param_2'])?','.$aField['add_param_2']:'' }}){!! isset($aField['nullable'])?'->nullable()':'' !!}{!! !empty($aField['default'])?"->default('".$aField['default']."')":"" !!};
    @endforeach
@if($oRequest->has('table_timestamps'))
        $table->timestamps();
@endif
        });
    }
    public function down()
    {
        Schema::dropIfExists('{{ strtolower($oRequest->input('table_name')) }}');
    }
}
