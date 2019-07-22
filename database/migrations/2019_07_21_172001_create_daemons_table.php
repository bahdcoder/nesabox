<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaemonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daemons', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('user');
            $table->string('command');
            $table->uuid('server_id');
            $table->integer('processes');
            $table->string('directory')->nullable();
            $table->string('status')->default('installing');
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
        Schema::dropIfExists('daemons');
    }
}
