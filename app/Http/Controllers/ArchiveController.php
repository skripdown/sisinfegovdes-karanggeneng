<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Models\Archivetype;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function archives($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Archivetype::with($models)->get();
        }
        else {
            $data   = Archivetype::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(\App\Http\back\authorize\Archive::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.archive"');
                _Activity::do('mengakses halaman permohonan arsip');
                return view('admin.archive',compact('data'));
            }
        }
        if (_Authorize::citizen() && $show) {
            _Log::log(_Log::$DANGER, 'sending get url failed on authorization error');
            return view('system.404');
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function insertType(Request $request):JsonResponse {

    }

    public function editType(Request $request):JsonResponse {

    }

    public function deleteType(Request $request):JsonResponse {

    }

    public function clearType(Request $request):JsonResponse {

    }

    public function downloadType($token) {

    }

    public function deleteArchive(Request $request):JsonResponse {

    }

    public function downloadArchive($token) {

    }

}
