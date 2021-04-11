<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Authorize;
use App\Http\back\_UI;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function photos($flag='') {
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show)
                return view('guest.login');
            return (object)null;
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Photo::with($models)->get();
        }
        else {
            $data   = Photo::all();
        }

        return $data;
    }
}
