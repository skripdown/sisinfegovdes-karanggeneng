<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Http\back\_Token;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\Registration;
use App\Models\Verification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function registration($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $id   = _UI::id($flag);
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show) {
                _Log::log(_Log::$WARNING, 'sending canceled on login required');
                return view('guest.login');
            }
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Registration::with($models)->where('nid', $id)->first();
        }
        else {
            $data   = Registration::all()->firstWhere('nid', $id);
        }

        if ($show) {
            _Log::log(_Log::$SUCCESS,'sending get url success with dump data');
            _Activity::do('mengakses data registrasi');
            dd($data);
        }
        else {
            _Log::log(_Log::$SUCCESS,'sending get url success with return data');
            _Activity::do('mengakses data registrasi');
            return $data;
        }
    }

    public function registrations($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show) {
                _Log::log(_Log::$WARNING, 'sending canceled on login required');
                return view('guest.login');
            }
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Registration::with($models)->get();
        }
        else {
            $data   = Registration::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.registration"');
                _Activity::do('mengakses halaman registrasi');
                _App::page('registrations', $flag);
                return view('admin.registration', compact('data'));
            }
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data registrasi');
        return $data;
    }

    public function insert(Request $request): JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert registration');
        if (Registration::all()->where('nid', $request->nid)->count() > 0) {
            $exist = Registration::all()->firstWhere('nid', $request->nid);
            if ($exist->verified)
                $status = ['status'=>'error','message'=>'permintaan data pengguna dengan Nomor '.$request->nid.' sudah diajukan sebelumnya.'];
            else
                $status = ['status'=>'error','message'=>'permintaan data pengguna dengan Nomor '.$request->nid.' sudah tersedia sebelumnya.'];
            _Log::log(_Log::$DANGER,'sending request insert registration failed');
        }
        else {
            $registration              = new Registration();
            $registration->name        = $request->name;
            $registration->nid         = $request->nid;
            $registration->email       = $request->email;
            $registration->phone       = $request->phone;
            $registration->password    = $request->password;
            $registration->gender      = $request->gender;
            $registration->day_birth   = $request->day_birth;
            $registration->month_birth = $request->month_birth;
            $registration->year_birth  = $request->year_birth;
            $registration->place_birth = $request->place_birth;
            $registration->pic         = _Image::setDefaultProfile($request->name, $request->nid,'_n');
            $registration->id_pic      = _Image::setIdCardPic($request->file('id_pic'), $request->nid,'_n');
            $registration->save();

            $status = ['status'=>'success','message'=>'permintaan data anda berhasil ditambahkan. Data akan diverifikasi oleh admin '.env('APP_NAME').' dan akan diberitahukan lewat email.'];
            _Log::log(_Log::$SUCCESS,'sending request insert registration success');
            _Activity::do('melakukan registrasi');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function verification(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request verify registration');
        $registration = Registration::all()->firstWhere('nid', $request->nid);
        $verify       = $request->action;
        if ($verify) {
            $registration->verified = true;
            $verification = new Verification();
            $verification->token = _Token::make(12);
            $verification->registration()->associate($registration);
            $verification->save();
        }
        else {
            $registration->verified = false;
            $registration->verification->delete();
        }
        $registration->save();
        _Log::log(_Log::$SUCCESS,'sending request verify registration success');
        _Activity::do('melakukan verifikasi data registrasi');

        return response()->json($request);
    }

    public function delete(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete registration');
        $registration = Registration::all()->firstWhere('nid', $request->nid);
        $registration->delete();

        _Log::log(_Log::$SUCCESS,'sending request delete registration success');
        _Activity::do('menghapus data registrasi');
        return response()->json($request);
    }

    public function clear(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request clear registration');
        $registrations = Registration::all();
        try {
            foreach ($registrations as $registration) {
                $registration->delete();
            }
            _Log::log(_Log::$SUCCESS,'sending request clear registration success');
            _Activity::do('membersihkan semua data registrasi');
        } catch (\Exception $e) {
            _Log::log(_Log::$DANGER,'sending request clear registration failed');
        }

        return response()->json($request);
    }
}
