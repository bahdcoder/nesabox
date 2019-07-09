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
            $table->string('name')->nullable();
            $table->text('key')->nullable();
            $table->uuid('server_id')->nullable();
            $table->boolean('is_app_key')->default(false);
            $table->string('provider')->nullable();
            $table->boolean('is_ready')->default(false);
            // this field is for cases where the ssh key was saved on an external server like digital ocean
            // in cases where it was created manually by a user, this field is null.
            $table->string('identifier')->nullable();
            $table->string('fingerprint')->nullable();
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
