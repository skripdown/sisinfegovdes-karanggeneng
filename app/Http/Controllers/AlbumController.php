<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Authorize;
use App\Http\back\_UI;
use App\Http\back\authorize\Publication;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    public function album($flag='') {
        return view('root.album_item');
    }

    public function albums($flag='') {
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);


        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Album::with($models)->get();
        }
        else {
            $data   = Album::all();
        }

        if ($show) {
            if (_Authorize::admin() && _Authorize::manage(Publication::class))
                return view('admin.album',compact('data'));
            if (_Authorize::citizen())
                return view('citizen.album',compact('data'));
            return view('guest.album', compact('data'));
        }

        return $data;
    }
}
