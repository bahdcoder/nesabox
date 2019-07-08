<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('ip')->nullable();
            $table->string('name')->nullable();
            $table->string('region')->nullable();
            $table->string('size')->nullable();
            $table->string('slug')->nullable();
            $table->text('details')->nullable();
            $table->text('ssh_key')->nullable();
            $table->string('provider')->nullable();
            $table->string('databases')->nullable();
            $table->string('status')->default('new');
            $table->string('identifier')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('node_version')->default('node');
            $table->json('ssh_key_added_to_source_provider')->nullable();
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
        Schema::dropIfExists('servers');
    }
}
