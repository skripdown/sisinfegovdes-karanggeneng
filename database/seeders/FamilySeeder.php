<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\District;
use App\Models\Family;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding family');
        $district  = District::all()->first();
        $hamlet    = $district->hamlets()->first();
        $neighboor = $hamlet->neighboors()->first();
        $family    = new Family(['number'=>'12321']);
        $family->district()->associate($district);
        $family->hamlet()->associate($hamlet);
        $family->neighboor()->associate($neighboor);
        $family->save();
        _Log::system(_Log::$SUCCESS, 'seeding family success');
    }
}
