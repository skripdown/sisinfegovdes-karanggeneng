<?php /** @noinspection SpellCheckingInspection */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqarchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reqarchives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token')->unique();
            $table->string('type');
            $table->string('code');
            $table->boolean('enable_public')->default(false);
            $table->bigInteger('archivetype');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('reqarchives');
    }
}
