<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deaths', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity')->unique();
            $table->string('pic')->unique();
            $table->integer('day_death');
            $table->integer('month_death');
            $table->integer('year_death');
            $table->enum('gender',['laki-laki','perempuan'])->default('laki-laki');
            $table->enum('blood',['a+','a-','b+','b-','ab+','ab-','o+','o-'])->default('a+');
            $table->string('family_number');
            $table->text('cause_of_death')->default('tidak ada');
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
        Schema::dropIfExists('deaths');
    }
}
