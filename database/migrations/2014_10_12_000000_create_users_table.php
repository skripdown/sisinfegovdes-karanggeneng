<?php /** @noinspection SpellCheckingInspection */

use App\Http\back\metasystem\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        System::init(
            'User', 'Pengguna',
            [
                ['label'=>'nama', 'pointer'=>'name'],
                ['label'=>'identitas', 'pointer'=>'identity'],
                ['label'=>'email', 'pointer'=>'email'],
                ['label'=>'telepon', 'pointer'=>'phone'],
                ['label'=>'foto', 'pointer'=>'pic'],
                ['label'=>'status_kawin', 'pointer'=>'marriage', 'model'=>'citizen', 'source'=>false],
            ]
        );
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('pic');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('usable')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
