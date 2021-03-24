<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirstTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_id');
            $table->string('uuid');
            $table->string('location')->nullable();
            $table->string('version');
            $table->string('number_of_contacts');
            $table->timestamps();
        });

        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->timestamps();
        });
    }
}
