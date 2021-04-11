<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['acc','del','mod','in','out','exp'])->default('acc');
            $table->string('from')->default('anonim');
            $table->bigInteger('mutation_target')->nullable();
            $table->bigInteger('officer_id')->unsigned();
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
        Schema::dropIfExists('approvals');
    }
}
