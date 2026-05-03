<?php
/**
* Created by PhpStorm.
* User: TiberLex
* Date: 11.05.2018
* Time: 12:50
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments("id");
            $table->integer('dialog_id');
            $table->integer('author_id');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('text');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
