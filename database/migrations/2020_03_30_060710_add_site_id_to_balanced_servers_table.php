<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteIdToBalancedServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balanced_servers', function (Blueprint $table) {
            $table->uuid('site_id');

            $table->dropColumn('server_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balanced_servers', function (Blueprint $table) {
            $table->uuid('server_id');

            $table->dropColumn('site_id');
        });
    }
}
