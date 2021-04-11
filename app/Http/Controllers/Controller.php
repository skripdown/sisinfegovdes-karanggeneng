<?php

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard() {
        _Log::log(_Log::$INFO, 'sending get url');
        if (!_Authorize::login()) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return redirect()->route('login');
        }
        if (_Authorize::admin() || _Authorize::chief()) {
            _Log::log(_Log::$SUCCESS, 'sending get url success with return "admin.dashboard"');
            _Activity::do('mengakses halaman dashboard');
            return view('admin.dashboard');
        }
        _Log::log(_Log::$SUCCESS, 'sending get url success with return "citizen.dashboard"');
        _Activity::do('mengakses halaman dashboard');
        return view('citizen.dashboard');
    }
}
