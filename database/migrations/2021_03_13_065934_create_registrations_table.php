<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nid');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->enum('gender',['laki-laki', 'perempuan']);
            $table->integer('day_birth');
            $table->integer('month_birth');
            $table->integer('year_birth');
            $table->string('place_birth');
            $table->string('id_pic');
            $table->string('pic');
            $table->boolean('verified')->default(false);
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
        Schema::dropIfExists('registrations');
    }
}
