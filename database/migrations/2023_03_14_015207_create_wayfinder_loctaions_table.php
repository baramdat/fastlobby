<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWayfinderLoctaionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wayfinder_loctaions', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
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
        Schema::dropIfExists('wayfinder_loctaions');
    }
}
