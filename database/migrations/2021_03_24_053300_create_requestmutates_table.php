<?php /** @noinspection SpellCheckingInspection */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestmutatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestmutates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('in');
            $table->boolean('me')->default(false);
            $table->bigInteger('user_target');
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
        Schema::dropIfExists('requestmutates');
    }
}
