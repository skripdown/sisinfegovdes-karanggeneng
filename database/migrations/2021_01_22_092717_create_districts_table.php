<?php /** @noinspection SpellCheckingInspection */

use App\Http\back\metasystem\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        System::init(
            'District', 'Dusun',
            [
                ['label'=>'nama', 'pointer'=>'name'],
                ['label'=>'luas', 'pointer'=>'size'],
            ]
        );
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('size');
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
        Schema::dropIfExists('districts');
    }
}
