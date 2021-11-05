<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class LaratrustSetupTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing teams
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            //Added this so we can know WHO (the user with the specific ID) authorized the creation of this team
            $table->integer('authorizer_id')->nullable()->default(null);
            //Added this so we can know WHO requested this team creation (and will become the leader of the team)
            $table->integer('user_leader_id')->nullable()->default(null);
            //Added this so we can know for WHICH clarisa project the tocFlow is created
            $table->integer('clarisa_project_id')->nullable()->default(null);
            //Boolean to let us know if a team is active or not.
            //If a team has the value of 0 (false), the team is not yet verified or it is deleted.
            $table->boolean('active')->default(0);
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
//            $table->string('description')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            // Drop role foreign key and primary key
            $table->dropForeign(['role_id']);
            $table->dropPrimary(['user_id', 'role_id', 'user_type']);

            // Add team_id column
            $table->unsignedInteger('team_id')->nullable();

            // Create foreign keys
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');

            // Create a unique key
            $table->unique(['user_id', 'role_id', 'user_type', 'team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            // Drop permission foreign key and primary key
            $table->dropForeign(['permission_id']);
            $table->dropPrimary(['permission_id', 'user_id', 'user_type']);

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            // Add team_id column
            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'permission_id', 'user_type', 'team_id']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::dropIfExists('teams');

    }
}
