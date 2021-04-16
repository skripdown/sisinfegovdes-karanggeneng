<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Archive;
use App\Models\Reqarchive;
use Illuminate\Http\Request;

class RequestArchiveController extends Controller
{
    public function requests($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Reqarchive::with($models)->get();
        }
        else {
            $data   = Reqarchive::all();
        }
        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Archive::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.request_archive"');
                _Activity::do('mengakses halaman permintaan arsip');
                _App::page('request_archives', $flag);
                return view('admin.request_archive',compact('data'));
            }
        }
        if (_Authorize::citizen() && $show) {
            _Log::log(_Log::$DANGER, 'sending get url failed on authorization error');
            return view('system.404');
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function makeRequest(Request $request) {

    }
}
