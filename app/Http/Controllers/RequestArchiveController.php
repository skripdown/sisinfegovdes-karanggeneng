<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Authorize;
use App\Http\back\_UI;
use App\Http\back\authorize\Archive;
use App\Models\Reqarchive;
use Illuminate\Http\Request;

class RequestArchiveController extends Controller
{
    public function requests($flag='') {
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show)
                return view('guest.login');
            return (object)null;
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Reqarchive::with($models)->get();
        }
        else {
            $data   = Reqarchive::all();
        }

        if (_Authorize::admin())
            if ($show && _Authorize::manage(Archive::class))
                return view('admin.request_archive',compact('data'));
        if (_Authorize::citizen())
            if ($show)
                return view('citizen.request_archive',compact('data'));

        return $data;
    }
}
