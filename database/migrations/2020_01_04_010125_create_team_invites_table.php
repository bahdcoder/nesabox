<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_invites', function (Blueprint $table) {
            $table->uuid('id');
            $table->bigInteger('user_id')->nullable();
            $table->uuid('team_id');
            $table->string('email');
            // this will tell us if a user is part of the team or not
            // for example, to get all members of a team, we'll
            // query all team invites where the status is
            // accepted.
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('team_invites');
    }
}
