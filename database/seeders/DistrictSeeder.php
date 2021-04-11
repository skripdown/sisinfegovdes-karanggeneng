<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding districts');
        $district = new District(['name'=>'Karanggeneng']);
        $district->size = 200000;
        $district->save();
        _Log::system(_Log::$SUCCESS, 'seeding districts success');
    }
}
