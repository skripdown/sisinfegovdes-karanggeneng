<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Authorize;
use App\Http\back\_UI;
use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function archives($flag='') {
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show)
                return view('guest.login');
            return (object)null;
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Archive::with($models)->get();
        }
        else {
            $data   = Archive::all();
        }

        if (_Authorize::admin())
            if ($show && _Authorize::manage(\App\Http\back\authorize\Archive::class))
                return view('admin.archive',compact('data'));
        if (_Authorize::citizen())
            if ($show)
                return view('citizen.archive',compact('data'));

        return $data;
    }
}
