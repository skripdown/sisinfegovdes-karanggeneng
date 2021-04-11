<?php

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding religions');
        $rel1 = new Religion(['name'=>'Islam']); $rel1->save();
        $rel2 = new Religion(['name'=>'Katolik']); $rel2->save();
        $rel3 = new Religion(['name'=>'Protestan']); $rel3->save();
        $rel4 = new Religion(['name'=>'Hindu']); $rel4->save();
        $rel5 = new Religion(['name'=>'Budha']); $rel5->save();
        $rel6 = new Religion(['name'=>'Kong Hu Chu']); $rel6->save();
        $rel7 = new Religion(['name'=>'Tidak Ada']); $rel7->save();
        _Log::system(_Log::$SUCCESS, 'seeding religions success');
    }
}
