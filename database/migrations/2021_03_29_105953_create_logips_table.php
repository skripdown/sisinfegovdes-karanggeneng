<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('log_id')->unsigned();
            $table->bigInteger('ip_id')->unsigned();
            $table->foreign('log_id')->references('id')->on('logs')->onDelete('cascade');
            $table->foreign('ip_id')->references('id')->on('ips')->onDelete('cascade');
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
        Schema::dropIfExists('logips');
    }
}
