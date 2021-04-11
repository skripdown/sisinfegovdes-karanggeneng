<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBirthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('births', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birth_number');
            $table->integer('day_birth');
            $table->integer('month_birth');
            $table->integer('year_birth');
            $table->string('place_birth');
            $table->enum('gender',['laki-laki','perempuan'])->default('laki-laki');
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
        Schema::dropIfExists('births');
    }
}
