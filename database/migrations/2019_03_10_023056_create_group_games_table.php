<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('game_id')->unsigned();
            $table->foreign('game_id')->references('id')->on('games');
            $table->integer('xf_user_group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_games');
    }
}
