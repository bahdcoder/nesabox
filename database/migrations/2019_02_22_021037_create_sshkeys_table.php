<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSshkeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sshkeys', function (Blueprint $table) {
            $table->uuid('id');
            $table->text('key')->nullable();
            $table->string('name')->nullable();
            $table->uuid('server_id')->nullable();
            $table->boolean('is_app_key')->default(false);
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
        Schema::dropIfExists('sshkeys');
    }
}
