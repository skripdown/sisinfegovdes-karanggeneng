<?php /** @noinspection SpellCheckingInspection */

use App\Http\back\metasystem\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeighboorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        System::init(
            'Neighboor', 'RW',
            [
                ['label'=>'nama', 'pointer'=>'name'],
            ]
        );
        Schema::create('neighboors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('hamlet_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('hamlet_id')->references('id')->on('hamlets')->onDelete('cascade');
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
        Schema::dropIfExists('neighboors');
    }
}
