<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAggregate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregate_contacts_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('number_of_contacts')->default(0);
            $table->timestamps();

            $table->index('date');
        });
        Schema::create('aggregate_contacts_weeks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('new')->default(0);
            $table->unsignedInteger('stale')->default(0);
            $table->unsignedInteger('number_of_contacts')->default(0);
            $table->timestamps();

            $table->index('date');
        });
        Schema::create('aggregate_contacts_months', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('new')->default(0);
            $table->unsignedInteger('stale')->default(0);
            $table->unsignedInteger('number_of_contacts')->default(0);
            $table->timestamps();

            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregate_contacts_months');
        Schema::dropIfExists('aggregate_contacts_weeks');
        Schema::dropIfExists('aggregate_contacts_days');
    }
}
