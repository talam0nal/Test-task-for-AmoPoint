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

class CreateDialogsTable extends Migration
{
    public function up()
    {
        Schema::create('dialogs', function (Blueprint $table) {
            $table->increments("id");
            $table->integer('sender_id');
            $table->integer('reader_id');
            $table->integer('messages_count');
            $table->boolean('published')->default('1');
            });
    }
    public function down()
    {
        Schema::dropIfExists('dialogs');
    }
}
