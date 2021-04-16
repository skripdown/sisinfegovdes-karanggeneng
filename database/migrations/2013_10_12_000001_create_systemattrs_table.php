<?php /** @noinspection SpellCheckingInspection */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemattrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systemattrs', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('pointer');
            $table->boolean('relation')->default(false);
            $table->boolean('source')->default(false);
            $table->string('model')->default('');
            $table->bigInteger('system_id')->unsigned();
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
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
        Schema::dropIfExists('systemattrs');
    }
}
