<?php

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function myActivity() {
        _Log::log(_Log::$INFO, 'sending get url');
        $show = _UI::show(';v=1');

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        $data = _Activity::getMy();

        _Log::log(_Log::$SUCCESS,'sending get url success with return "root.activity"');
        _Activity::do('mengakses halaman aktivitas');
        _App::page('my_activities');
        return view('root.activity', compact('data'));
    }

    public function devActivity() {
        _Log::log(_Log::$INFO, 'sending get url');
        $show = _UI::show(';v=1');

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if (!(_Authorize::manage(Developer::class) || _Authorize::chief())) {
            _Log::log(_Log::$DANGER, 'authorization not valid');
            return view('system.404');
        }

        $data = _Activity::getDev();
        _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.activity"');
        _Activity::do('mengakses halaman aktivitas pengguna');
        _App::page('dev_activities');
        return view('admin.activity', compact('data'));
    }

    public function clearMy(Request $request): JsonResponse
    {
        return response()->json(_Activity::meClear($request));
    }

    public function clearDev(Request $request): JsonResponse {
        return response()->json(_Activity::devClear($request));
    }

    public function deleteActivity(Request $request):JsonResponse {
        return response()->json(_Activity::delete($request));
    }
}
