<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\District;
use App\Models\Hamlet;
use Illuminate\Database\Seeder;

class HamletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding hamlet');
        $district = District::all()->first();
        $district->hamlets()->save(new Hamlet(['name'=>'01']));
        _Log::system(_Log::$SUCCESS, 'seeding hamlet success');
    }
}
