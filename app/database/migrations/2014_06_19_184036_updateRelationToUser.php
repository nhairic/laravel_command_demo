<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRelationToUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('articles', function($table) {
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')
                            ->references('id')->on('users')
                            ->onDelete('cascade');
            }); //	//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
