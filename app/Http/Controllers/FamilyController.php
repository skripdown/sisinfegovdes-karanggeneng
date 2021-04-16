<?php
/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function families($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Family::with($models)->get();
        }
        else {
            $data   = Family::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Activity::do('mengakses halaman keluarga');
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.family"');
                _App::page('families', $flag);
                return view('admin.family',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Activity::do('mengakses halaman keluarga');
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.family"');
                return view('citizen.family',compact('data'));
            }
        }

        _Activity::do('mengakses data keluarga');
        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }
}
