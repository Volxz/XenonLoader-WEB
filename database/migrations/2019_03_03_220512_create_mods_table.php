<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModsTable extends Migration {

	public function up()
	{
		Schema::create('mods', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->softDeletes();

            $table->double('version');
            $table->string('secret');
			$table->string('name');
            $table->string('mod_file')->nullable();
            $table->integer('game_id')->unsigned()->nullable();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();

        });
	}

	public function down()
	{
		Schema::drop('mods');
	}
}