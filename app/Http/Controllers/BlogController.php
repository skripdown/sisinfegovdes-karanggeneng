<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Authorize;
use App\Http\back\_UI;
use App\Http\back\authorize\Publication;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog($flag='') {
        return view('root.blog_item');
    }

    public function blogs($flag='') {
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);


        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Blog::with($models)->get();
        }
        else {
            $data   = Blog::all();
        }

        if ($show) {
            if (_Authorize::admin() && _Authorize::manage(Publication::class))
                return view('admin.blog',compact('data'));
            if (_Authorize::citizen())
                return view('citizen.blog',compact('data'));
            return view('guest.blog', compact('data'));
        }

        return $data;
    }
}
