<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\Archivetype;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArchivetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding archive type');
        $user = User::with(['officer'])->first();
        $type = new Archivetype();
        $type->name  = 'kepegawaian';
        $type->code  = 'AR01';
        $type->token = Archivetype::makeToken();
        $type->folder_path = Archivetype::makeDir('AR01');
        $type->officer()->associate($user->officer);
        $type->save();

        $type = new Archivetype();
        $type->name  = 'kependudukan';
        $type->code  = 'AR02';
        $type->token = Archivetype::makeToken();
        $type->folder_path = Archivetype::makeDir('AR02');
        $type->officer()->associate($user->officer);
        $type->save();
        _Log::system(_Log::$SUCCESS, 'seeding archive type success');
    }
}
