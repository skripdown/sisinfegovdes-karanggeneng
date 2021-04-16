<?php /** @noinspection SpellCheckingInspection */

use App\Http\back\metasystem\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        System::init(
            'Officer', 'Pegawai',
            [
                ['label'=>'NIP', 'pointer'=>'identity'],
                ['label'=>'jenis', 'pointer'=>'status'],
                ['label'=>'pangkat_jabatan', 'pointer'=>'rank'],
                ['label'=>'golongan', 'pointer'=>'set'],
                ['label'=>'ruang', 'pointer'=>'room'],
                ['label'=>'posisi', 'pointer'=>'occupation'],
                ['label'=>'gaji', 'pointer'=>'salary'],
                ['label'=>'status', 'pointer'=>'regis'],
            ]
        );
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('identity')->default('');
            $table->enum('status',['asn','honorer'])->default('asn');
            $table->string('rank');
            $table->string('set');
            $table->string('room');
            $table->string('occupation');
            $table->bigInteger('salary');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('regis', ['in', 'out'])->default('in');
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
        Schema::dropIfExists('officers');
    }
}
