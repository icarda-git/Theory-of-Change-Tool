<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTeamInvitationUnregistered extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_team_invitation_unregistered', function (Blueprint $table) {
            // Add team_id column
            $table->increments('id');
            $table->unsignedInteger('team_id')->nullable();
            $table->string('user_email');
            $table->unsignedBigInteger('role_id')->unsigned();
            $table->timestamps();
        });


//        Schema::table('user_team_invitation', function($table) {
//            $table->foreign('team_id')->references('id')->on('teams');
//            $table->foreign('role_id')->references('id')->on('roles');
//        });
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
