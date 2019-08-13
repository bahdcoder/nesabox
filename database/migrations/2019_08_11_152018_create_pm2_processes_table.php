<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePm2ProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm2_processes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('command')->nullable();
            $table->string('status')->nullable();
            $table->string('logs_path');
            $table->uuid('site_id');
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
        Schema::dropIfExists('pm2_processes');
    }
}
