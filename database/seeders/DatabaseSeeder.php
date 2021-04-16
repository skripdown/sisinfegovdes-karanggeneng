<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'deploying system');
        $this->call([
            ReligionSeeder::class,
            EducationSeeder::class,
            OccupationSeeder::class,
            DistrictSeeder::class,
            HamletSeeder::class,
            NeighboorSeeder::class,
            FamilySeeder::class,
            UserSeeder::class,
            CitizenSeeder::class,
            ArchivetypeSeeder::class,
        ]);
        _Log::system(_Log::$SUCCESS, 'deploying system success');
    }
}
