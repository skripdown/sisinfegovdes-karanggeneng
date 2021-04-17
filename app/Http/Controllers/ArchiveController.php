<?php /** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Archive;
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
                _App::page('archives', $flag);
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
        _Log::log(_Log::$INFO,'sending request insert new archivetype');
        $name = $request->name;
        if (_Authorize::manage(Archive::class)) {
            if (Archivetype::all()->where('name', $name)->count() == 0) {
                $new        = new Archivetype();
                $new->name  = $name;
                $code       = Archivetype::makeCode();
                $new->code  = $code;
                $new->token = Archivetype::makeToken();
                $new->folder_path = Archivetype::makeDir($code);
                $new->officer()->associate(_Authorize::data()->officer);
                $new->save();
                $new = Archivetype::with(['officer','officer.user','archives','archives.archivefiles','archives.officer','archives.officer.user'])->firstWhere('id', $new->id);
                $status  = ['status'=>'success','message'=>'Berhasil menambahkan tipe arsip', 'archivetype'=>$new];
                _Log::log(_Log::$SUCCESS,'sending request insert new archivetype success');
                _Activity::do('menambahkan tipe arsip ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Arsip '.$name.' sudah tersedia'];
                _Log::log(_Log::$DANGER,'sending request insert new archivetype failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request insert new archivetype failed');
        }

        return response()->json(array_merge($request->all(), $status));
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
