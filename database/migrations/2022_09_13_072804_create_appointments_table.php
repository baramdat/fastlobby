<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->dateTime('visiting_time')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->longText('visiting_address')->nullable();
            $table->longText('unique_code')->nullable();
            $table->longText('qr_code')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('appointments');
    }
}
