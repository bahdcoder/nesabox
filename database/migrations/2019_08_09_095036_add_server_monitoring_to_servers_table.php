<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerMonitoringToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('server_monitoring_status')->nullable();
            $table->string('server_monitoring_username')->nullable();
            $table->string('server_monitoring_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('server_monitoring_status');
            $table->dropColumn('server_monitoring_username');
            $table->dropColumn('server_monitoring_password');
        });
    }
}
