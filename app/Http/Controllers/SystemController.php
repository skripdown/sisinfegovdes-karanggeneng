<?php

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Log;

class SystemController extends Controller
{
    public function route($link) {
        _Log::log(_Log::$INFO,'sending get request with param "'.$link.'"');
        $view = view('system.404');
        if ($link == 'kependudukan') {
            _Log::log(_Log::$SUCCESS,'sending get request success with redirect to "kependudukan"');
            _Activity::do('mengakses halaman awal kependudukan');
            $view = view('system.kependudukan');
        }
        elseif ($link == 'kepegawaian') {
            _Log::log(_Log::$SUCCESS,'sending get request success with redirect to "kepegawaian"');
            _Activity::do('mengakses halaman awal kepegawaian');
            $view = view('system.kepegawaian');
        }
        elseif ($link == 'surat') {
            _Log::log(_Log::$SUCCESS,'sending get request success with redirect to "surat"');
            _Activity::do('mengakses halaman awal surat');
            $view = view('system.surat');
        }
        else {
            _Log::log(_Log::$DANGER,'sending get request failed with return "404"');
        }

        return $view;
    }
}
