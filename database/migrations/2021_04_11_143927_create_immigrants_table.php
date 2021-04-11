<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmigrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immigrants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity')->unique();
            $table->string('pic')->unique();
            $table->enum('gender',['laki-laki','perempuan'])->default('laki-laki');
            $table->string('to_province');
            $table->string('to_regency');
            $table->string('family_number');
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
        Schema::dropIfExists('immigrants');
    }
}
