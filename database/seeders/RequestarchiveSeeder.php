<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */

namespace Database\Seeders;

use App\Models\Archivetype;
use App\Models\Reqarchive;
use App\Models\User;
use Illuminate\Database\Seeder;

class RequestarchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type     = Archivetype::all()->firstWhere('id', 2);
        $user1    = User::all()->firstWhere('id', 4);
        $user2    = User::all()->firstWhere('id', 5);
        $request1 = new Reqarchive();
        $request2 = new Reqarchive();

        $request1->name          = 'surat keterangan pindah kota';
        $request1->token         = Archivetype::makeToken(30);
        $request1->type          = $type->name;
        $request1->code          = $type->code;
        $request1->enable_public = true;
        $request1->archivetype   = $type->id;
        $request1->user()->associate($user1);
        $request1->save();

        $request2->name          = 'surat keterangan tidak mampu';
        $request2->token         = Archivetype::makeToken(30);
        $request2->type          = $type->name;
        $request2->code          = $type->code;
        $request2->enable_public = false;
        $request2->archivetype   = $type->id;
        $request2->user()->associate($user2);
        $request2->save();
    }
}
