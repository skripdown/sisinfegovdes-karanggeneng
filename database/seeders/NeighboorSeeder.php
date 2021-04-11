<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\District;
use App\Models\Neighboor;
use Illuminate\Database\Seeder;

class NeighboorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding neighbor');
        $district  = District::all()->first();
        $hamlet    = $district->hamlets()->first();
        $neighboor = new Neighboor(['name'=>'003']);
        $neighboor->district()->associate($district);
        $neighboor->hamlet()->associate($hamlet);
        $neighboor->save();
        _Log::system(_Log::$SUCCESS, 'seeding neighbor success');
    }
}
