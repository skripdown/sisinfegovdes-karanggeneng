<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modifies', function (Blueprint $table) {
            $table->id();
            $table->string('attribute');
            $table->string('value');
            $table->enum('type', ['int', 'str', 'bool','big'])->default('str');
            $table->bigInteger('approval_id')->unsigned();
            $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
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
        Schema::dropIfExists('modifies');
    }
}
