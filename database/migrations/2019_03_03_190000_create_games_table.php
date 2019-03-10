<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	public function up()
	{
		Schema::create('games', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('executable');
		});
	}

	public function down()
	{
		Schema::drop('games');
	}
}