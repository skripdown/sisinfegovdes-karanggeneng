<?php

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\authorize\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function adminOn(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request switch admin mode on');
        if (_Authorize::admin()) {
            _App::modeAdmin(true);
            _Log::log(_Log::$SUCCESS,'sending request switch admin mode on success');
            _Activity::do('mengaktifkan mode admin');
        } else {
            _Log::log(_Log::$DANGER,'sending request switch admin mode on failed');
        }

        return response()->json($request->all());
    }

    public function adminOff(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request switch admin mode off');
        if (_Authorize::admin()) {
            _App::modeAdmin(false);
            _Log::log(_Log::$SUCCESS,'sending request switch admin mode off success');
            _Activity::do('menonaktifkan mode admin');
        } else {
            _Log::log(_Log::$DANGER,'sending request switch admin mode off failed');
        }

        return response()->json($request->all());
    }

    public function devOn(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request switch developer mode on');
        if (_Authorize::manage(Developer::class) || _Authorize::chief()) {
            _App::modeDeveloper(true);
            _Log::log(_Log::$SUCCESS,'sending request switch developer mode on success');
            _Activity::do('mengaktifkan mode developer');
        } else {
            _Log::log(_Log::$DANGER,'sending request switch developer mode on failed');
        }

        return response()->json($request->all());
    }

    public function devOff(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request switch developer mode off');
        if (_Authorize::manage(Developer::class) || _Authorize::chief()) {
            _App::modeDeveloper(false);
            _Log::log(_Log::$SUCCESS,'sending request switch developer mode off success');
            _Activity::do('menonaktifkan mode developer');
        } else {
            _Log::log(_Log::$DANGER,'sending request switch developer mode off failed');
        }

        return response()->json($request->all());
    }

}
