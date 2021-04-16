<?php /** @noinspection SpellCheckingInspection */

use App\Http\back\metasystem\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        System::init(
            'Citizen', 'Penduduk',
            [
                ['label'=>'nama', 'pointer'=>'name'],
                ['label'=>'NIK', 'pointer'=>'identity'],
                ['label'=>'foto', 'pointer'=>'pic'],
                ['label'=>'tanggal_lahir', 'pointer'=>'day_birth'],
                ['label'=>'bulan_lahir', 'pointer'=>'month_birth'],
                ['label'=>'tahun_lahir', 'pointer'=>'year_birth'],
                ['label'=>'tempat_lahir', 'pointer'=>'place_birth'],
                ['label'=>'kawin', 'pointer'=>'marriage'],
                ['label'=>'kelamin', 'pointer'=>'gender'],
                ['label'=>'darah', 'pointer'=>'blood'],
                ['label'=>'telepon', 'pointer'=>'phone', 'model'=>'user', 'source'=>true],
            ]
        );
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity')->unique();
            $table->string('pic')->unique();
            $table->integer('day_birth');
            $table->integer('month_birth');
            $table->integer('year_birth');
            $table->string('place_birth');
            $table->boolean('marriage')->default(false);
            $table->enum('gender',['laki-laki','perempuan'])->default('laki-laki');
            $table->enum('blood',['a+','a-','b+','b-','ab+','ab-','o+','o-'])->default('a+');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('hamlet_id')->unsigned();
            $table->bigInteger('neighboor_id')->unsigned();
            $table->bigInteger('family_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('hamlet_id')->references('id')->on('hamlets')->onDelete('cascade');
            $table->foreign('neighboor_id')->references('id')->on('neighboors')->onDelete('cascade');
            $table->foreign('family_id')->references('id')->on('families')->onDelete('cascade');
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
        Schema::dropIfExists('citizens');
    }
}
