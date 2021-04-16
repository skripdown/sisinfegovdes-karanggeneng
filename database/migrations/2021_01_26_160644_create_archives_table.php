<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('archivetype_id')->unsigned();
            $table->bigInteger('officer_id')->unsigned();
            $table->string('token')->unique();
            $table->string('filename');
            $table->string('extension');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('archivetype_id')->references('id')->on('archivetypes')->onDelete('cascade');
            $table->foreign('officer_id')->references('id')->on('officers')->onDelete('cascade');
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
        Schema::dropIfExists('archives');
    }
}
