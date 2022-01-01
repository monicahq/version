<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('pings', 'uuid')) {
            Schema::table('pings', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
        if (Schema::hasColumn('pings', 'location')) {
            Schema::table('pings', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }

        Schema::table('hosts', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        Schema::table('pings', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('host_id')->change();
            $table->unsignedInteger('number_of_contacts')->change();
            $table->foreign('host_id')->references('id')->on('hosts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->dropForeign(['host_id']);
        });
        Schema::table('pings', function (Blueprint $table) {
            $table->integer('host_id')->change();
        });
    }
}
