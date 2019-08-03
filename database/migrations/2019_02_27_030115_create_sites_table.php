<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('server_id');
            $table->text('logs')->nullable();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->string('app_type')->nullable();
            $table->text('environment')->nullable();
            $table->string('repository')->nullable();
            // $table->text('deploy_script')->nullable();
            $table->string('slack_channel')->nullable();
            $table->boolean('deploying')->default(false);
            $table->boolean('quick_deploy')->default(false);
            $table->string('repository_status')->nullable();
            $table->string('node_version')->default('node');
            $table->string('repository_provider')->nullable();
            $table->string('repository_branch')->default('master');
            $table->string('installing_ghost_status')->nullable();
            $table->boolean('wild_card_subdomains')->default(false);
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
        Schema::dropIfExists('sites');
    }
}
