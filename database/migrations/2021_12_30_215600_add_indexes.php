<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pings', function (Blueprint $table) {
            $table->index('created_at');
        });
        Schema::table('hosts', function (Blueprint $table) {
            $table->index('uuid');
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
            $table->dropIndex(['created_at']);
        });
        Schema::table('hosts', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
        });
    }
}
