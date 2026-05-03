<?php echo '<?php'; ?>

/**
 * Created by Velgir
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ $sMigrationClassName }}Table extends Migration
{
    public function up()
    {
        Schema::create("{{ $aRelationship['join_table'] }}", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("{{ $aRelationship['foreign_key'] }}");
            $table->integer("{{ $aRelationship['other_key'] }}");
        });
    }

    public function down()
    {
        Schema::dropIfExists("{{ $aRelationship['join_table'] }}");
    }
}