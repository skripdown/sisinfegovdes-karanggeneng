<?php /** @noinspection SpellCheckingInspection */

namespace Database\Seeders;

use App\Http\back\_Log;
use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding occupations');
        $occ1 = new Occupation(['name'=>'ASN']);
        $occ2 = new Occupation(['name'=>'Honorer']);
        $occ3 = new Occupation(['name'=>'Wiraswasta']);
        $occ4 = new Occupation(['name'=>'Pelajar']);
        $occ5 = new Occupation(['name'=>'Petani']);
        $occ6 = new Occupation(['name'=>'Lain-lain']);
        $occ7 = new Occupation(['name'=>'Tidak ada']);

        $occ1->save();
        $occ2->save();
        $occ3->save();
        $occ4->save();
        $occ5->save();
        $occ6->save();
        $occ7->save();
        _Log::system(_Log::$SUCCESS, 'seeding occupations success');
    }
}
