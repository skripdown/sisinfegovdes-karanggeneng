<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Database\Seeders;

use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Models\Citeducation;
use App\Models\Citizen;
use App\Models\Citoccupation;
use App\Models\Citreligion;
use App\Models\District;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding citizens');
        $district   = District::all()->first();
        $hamlet     = $district->hamlets()->first();
        $neighboor  = $hamlet->neighboors()->first();
        $family     = $neighboor->families()->first();
        $education  = Education::all()->firstWhere('id', 5);
        $religion   = Religion::all()->firstWhere('id', 4);
        $occupation = Occupation::all()->firstWhere('id', 4);
        $user1      = new User(['name'=>'Rina Fachriani Putri', 'identity'=>'111222333', 'email'=>'rina123@sisegov.test', 'phone'=>'09876876543', 'password'=>Hash::make('ctz1pass'), 'pic'=>_Image::setDefaultProfile('Rina Fachriani Putri', 'ctz_1', 'citizen', false), 'usable'=>true]);
        $user1->save();
        $citizen1   = new Citizen(['name'=>$user1->name, 'identity'=>$user1->identity, 'pic'=>$user1->pic, 'gender'=>'perempuan','blood'=>'b+','day_birth'=>16,'month_birth'=>11,'year_birth'=>1997,'place_birth'=>'Surabaya']);
        $citizen1->district()->associate($district);
        $citizen1->hamlet()->associate($hamlet);
        $citizen1->neighboor()->associate($neighboor);
        $citizen1->family()->associate($family);
        $citizen1->user()->associate($user1);
        $citizen1->save();
        $ctz_education  = new Citeducation();
        $ctz_education->citizen()->associate($citizen1);
        $ctz_education->education()->associate($education);
        $ctz_education->save();
        $ctz_occupation = new Citoccupation();
        $ctz_occupation->citizen()->associate($citizen1);
        $ctz_occupation->occupation()->associate($occupation);
        $ctz_occupation->save();
        $ctz_religion   = new Citreligion();
        $ctz_religion->citizen()->associate($citizen1);
        $ctz_religion->religion()->associate($religion);
        $ctz_religion->save();

        $education  = Education::all()->firstWhere('id', 2);
        $religion   = Religion::all()->firstWhere('id', 1);
        $occupation = Occupation::all()->firstWhere('id', 1);
        $user2      = new User(['name'=>'Nikola Fadila Tesla', 'identity'=>'333222111', 'email'=>'niko123@sisegov.test', 'phone'=>'1029384756', 'password'=>Hash::make('ctz2pass'), 'pic'=>_Image::setDefaultProfile('Nikola Fadila Tesla', 'ctz_2', 'citizen', false), 'usable'=>true]);
        $user2->save();
        $citizen2   = new Citizen(['name'=>$user2->name, 'identity'=>$user2->identity, 'pic'=>$user2->pic, 'gender'=>'laki-laki','blood'=>'ab+','day_birth'=>18,'month_birth'=>6,'year_birth'=>2002,'place_birth'=>'Malang']);
        $citizen2->district()->associate($district);
        $citizen2->hamlet()->associate($hamlet);
        $citizen2->neighboor()->associate($neighboor);
        $citizen2->family()->associate($family);
        $citizen2->user()->associate($user2);
        $citizen2->save();
        $ctz_religion   = new Citreligion();
        $ctz_religion->citizen()->associate($citizen2);
        $ctz_religion->religion()->associate($religion);
        $ctz_religion->save();
        $ctz_education  = new Citeducation();
        $ctz_education->citizen()->associate($citizen2);
        $ctz_education->education()->associate($education);
        $ctz_education->save();
        $ctz_occupation = new Citoccupation();
        $ctz_occupation->citizen()->associate($citizen2);
        $ctz_occupation->occupation()->associate($occupation);
        $ctz_occupation->save();

        _Log::system(_Log::$SUCCESS, 'seeding citizens success');
    }
}
