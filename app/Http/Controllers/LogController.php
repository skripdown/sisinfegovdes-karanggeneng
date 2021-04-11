<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function logs($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if (!(_Authorize::manage(Civil::class) || _Authorize::chief())) {
            _Log::log(_Log::$DANGER, 'authorization not valid');
            return view('system.404');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Log::with($models)->get();
        }
        else {
            $data   = Log::all();
        }

        if ($show) {
            _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.logs"');
            _Activity::do('mengakses halaman log sistem');
            return view('admin.log', compact('data'));
        }

        _Activity::do('mengakses datalog sistem');
        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function clear(Request $request):JsonResponse {
        return response()->json(_Log::clear($request));
    }
}
