<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding educations');
        $ed1 = new Education(['name'=>'TK']);$ed1->save();
        $ed2 = new Education(['name'=>'SD']);$ed2->save();
        $ed3 = new Education(['name'=>'SMP']);$ed3->save();
        $ed4 = new Education(['name'=>'SMU']);$ed4->save();
        $ed5 = new Education(['name'=>'Diploma']);$ed5->save();
        $ed6 = new Education(['name'=>'Sarjana']);$ed6->save();
        $ed7 = new Education(['name'=>'Magister']);$ed7->save();
        $ed8 = new Education(['name'=>'Doktor']);$ed8->save();
        $ed9 = new Education(['name'=>'Tidak Ada']);$ed9->save();
        _Log::system(_Log::$SUCCESS, 'seeding educations success');
    }
}
