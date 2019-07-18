<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsProfileKeyFieldToSshkeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sshkeys', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->boolean('is_profile_key')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sshkeys', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('is_profile_key');
        });
    }
}
