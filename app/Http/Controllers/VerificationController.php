<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Models\Citeducation;
use App\Models\Citizen;
use App\Models\Citoccupation;
use App\Models\Citreligion;
use App\Models\District;
use App\Models\Education;
use App\Models\Family;
use App\Models\Occupation;
use App\Models\Religion;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function verify($flag='') {
        _Log::log(_Log::$INFO,'sending request verify registration');
        $token = _UI::id($flag);
        if (Verification::all()->where('token', $token)->count() > 0) {
            $registration   = Verification::all()->firstWhere('token', $token)->registration;
            $district   = District::all()->first();
            $hamlet     = $district->hamlets()->first();
            $neighboor  = $hamlet->neighboors()->first();
            $family     = new Family(['number'=>$registration->nid]);
            $family->district()->associate($district);
            $family->hamlet()->associate($hamlet);
            $family->neighboor()->associate($neighboor);
            $family->save();
            $education  = Education::all()->first();
            $religion   = Religion::all()->first();
            $occupation = Occupation::all()->first();
            $user           = new User();
            $user->name     = $registration->name;
            $user->identity = $registration->nid;
            $user->email    = $registration->email;
            $user->phone    = $registration->phone;
            $user->password = Hash::make($registration->password);
            $user->pic      = $registration->pic;
            $user->usable   = true;
            $user->save();
            _App::new($user);
            $citizen              = new Citizen();
            $citizen->name        = $registration->name;
            $citizen->identity    = $registration->nid;
            $citizen->pic         = $registration->pic;
            $citizen->gender      = $registration->gender;
            $citizen->day_birth   = $registration->day_birth;
            $citizen->month_birth = $registration->month_birth;
            $citizen->year_birth  = $registration->year_birth;
            $citizen->place_birth = $registration->place_birth;
            $citizen->blood       = 'a+';
            $citizen->district()->associate($district);
            $citizen->hamlet()->associate($hamlet);
            $citizen->neighboor()->associate($neighboor);
            $citizen->family()->associate($family);
            $citizen->user()->associate($user);
            $citizen->save();
            $ctz_rel = new Citreligion();
            $ctz_rel->citizen()->associate($citizen);
            $ctz_rel->religion()->associate($religion);
            $ctz_rel->save();
            $ctz_edu = new Citeducation();
            $ctz_edu->citizen()->associate($citizen);
            $ctz_edu->education()->associate($education);
            $ctz_edu->save();
            $ctz_occ = new Citoccupation();
            $ctz_occ->citizen()->associate($citizen);
            $ctz_occ->occupation()->associate($occupation);
            $ctz_occ->save();
            $registration->delete();
            Auth::login($user);

            _Log::log(_Log::$SUCCESS,'sending request verify registration success');
            _Activity::do('malakukan verifikasi registrasi kependudukan');
            return redirect()->route('settings');
        }
        else {
            _Log::log(_Log::$DANGER,'sending request verify registration failed');
            return view('system.404');
        }
    }
}
