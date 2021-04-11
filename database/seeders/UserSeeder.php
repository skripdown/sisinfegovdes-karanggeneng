<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */

namespace Database\Seeders;

use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Models\Citeducation;
use App\Models\Citizen;
use App\Models\CitizenEducation;
use App\Models\CitizenReligion;
use App\Models\Citoccupation;
use App\Models\Citreligion;
use App\Models\District;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\Officer;
use App\Models\Religion;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        _Log::system(_Log::$INFO, 'seeding users');
        $district   = District::all()->first();
        $hamlet     = $district->hamlets()->first();
        $neighboor  = $hamlet->neighboors()->first();
        $family     = $neighboor->families()->first();
        $education  = Education::all()->first();
        $religion   = Religion::all()->first();
        $occupation = Occupation::all()->first();
        $adm1 = new User(['name'=>'Mang Oleh', 'identity'=>'1234567890', 'email'=>'mangoleh123@sisegov.test', 'phone'=>'081343373983', 'password'=>Hash::make('adm1pass'), 'pic'=>_Image::setDefaultProfile('Mang Oleh', 'adm_1', 'admin', true), 'usable'=>true]);
        $adm1->save();
        $adm1->role()->save(new Role(['admin'=>true, 'account'=>true, 'publication'=>true, 'civil'=>true, 'employee'=>true, 'archive'=>true, 'developer'=>true]));
        $adm1->officer()->save(new Officer(['identity'=>'123123123','status'=>'asn', 'rank'=>'pembina utama', 'set'=>'iv', 'room'=>'e', 'occupation'=>'admin', 'salary'=>5000000,'regis'=>'in']));
        $ctz = new Citizen(['name'=>$adm1->name, 'identity'=>$adm1->identity, 'pic'=>$adm1->pic, 'gender'=>'laki-laki','blood'=>'a+','day_birth'=>10,'month_birth'=>8,'year_birth'=>1995,'place_birth'=>'Lamongan']);
        $ctz->district()->associate($district);
        $ctz->hamlet()->associate($hamlet);
        $ctz->neighboor()->associate($neighboor);
        $ctz->family()->associate($family);
        $ctz->user()->associate($adm1);
        $ctz->save();
        $ctz_religion   = new Citreligion();
        $ctz_religion->citizen()->associate($ctz);
        $ctz_religion->religion()->associate($religion);
        $ctz_religion->save();
        $ctz_education  = new Citeducation();
        $ctz_education->citizen()->associate($ctz);
        $ctz_education->education()->associate($education);
        $ctz_education->save();
        $ctz_occupation = new Citoccupation();
        $ctz_occupation->citizen()->associate($ctz);
        $ctz_occupation->occupation()->associate($occupation);
        $ctz_occupation->save();

        $education  = Education::all()->firstWhere('id', 4);
        $religion   = Religion::all()->firstWhere('id', 1);
        $occupation = Occupation::all()->firstWhere('id', 1);
        $adm2       = new User(['name'=>'Malik Fajar Lapele', 'identity'=>'201610370311138', 'email'=>'malko18@sisegov.test', 'phone'=>'081242824041', 'password'=>Hash::make('head1pass'), 'pic'=>_Image::setDefaultProfile('Malik Lapele', 'head_1', 'admin', true), 'usable'=>true]);
        $adm2->save();
        $adm2->role()->save(new Role(['chief'=>true]));
        $adm2->officer()->save(new Officer(['identity'=>'201610370311138','status'=>'asn', 'rank'=>'pembina utama', 'set'=>'iv', 'room'=>'e', 'occupation'=>'head', 'salary'=>15000000,'regis'=>'in']));
        $ctz2 = new Citizen(['name'=>$adm2->name, 'identity'=>$adm2->identity, 'pic'=>$adm2->pic, 'gender'=>'laki-laki','blood'=>'b+','day_birth'=>18,'month_birth'=>3,'year_birth'=>1998,'place_birth'=>'Ambon']);
        $ctz2->district()->associate($district);
        $ctz2->hamlet()->associate($hamlet);
        $ctz2->neighboor()->associate($neighboor);
        $ctz2->family()->associate($family);
        $ctz2->user()->associate($adm2);
        $ctz2->save();
        $ctz_religion   = new Citreligion();
        $ctz_religion->citizen()->associate($ctz2);
        $ctz_religion->religion()->associate($religion);
        $ctz_religion->save();
        $ctz_education  = new Citeducation();
        $ctz_education->citizen()->associate($ctz2);
        $ctz_education->education()->associate($education);
        $ctz_education->save();
        $ctz_occupation = new Citoccupation();
        $ctz_occupation->citizen()->associate($ctz2);
        $ctz_occupation->occupation()->associate($occupation);
        $ctz_occupation->save();

        $adm2       = new User(['name'=>'Erwin Fachriani', 'identity'=>'201610370311139', 'email'=>'erwin12@sisegov.test', 'phone'=>'08323284893', 'password'=>Hash::make('adm2pass'), 'pic'=>_Image::setDefaultProfile('Erwin Fachriani', 'adm_2', 'admin', true), 'usable'=>true]);
        $adm2->save();
        $adm2->role()->save(new Role(['admin'=>true, 'account'=>true, 'publication'=>true,]));
        $adm2->officer()->save(new Officer(['identity'=>'10102929','status'=>'asn', 'rank'=>'pembina utama', 'set'=>'iv', 'room'=>'e', 'occupation'=>'head', 'salary'=>15000000,'regis'=>'in']));
        $ctz2 = new Citizen(['name'=>$adm2->name, 'identity'=>$adm2->identity, 'pic'=>$adm2->pic, 'gender'=>'laki-laki','blood'=>'b+','day_birth'=>3,'month_birth'=>7,'year_birth'=>1966,'place_birth'=>'Sidoarjo']);
        $ctz2->district()->associate($district);
        $ctz2->hamlet()->associate($hamlet);
        $ctz2->neighboor()->associate($neighboor);
        $ctz2->family()->associate($family);
        $ctz2->user()->associate($adm2);
        $ctz2->save();
        $ctz_religion   = new Citreligion();
        $ctz_religion->citizen()->associate($ctz2);
        $ctz_religion->religion()->associate($religion);
        $ctz_religion->save();
        $ctz_education  = new Citeducation();
        $ctz_education->citizen()->associate($ctz2);
        $ctz_education->education()->associate($education);
        $ctz_education->save();
        $ctz_occupation = new Citoccupation();
        $ctz_occupation->citizen()->associate($ctz2);
        $ctz_occupation->occupation()->associate($occupation);
        $ctz_occupation->save();
        _Log::system(_Log::$SUCCESS, 'seeding users success');
    }
}
