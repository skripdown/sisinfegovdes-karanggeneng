<?php
/** @noinspection PhpParamsInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedMethodInspection */
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
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Zip;

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
            if ($show && _Authorize::manage(Archive::class)) {
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
        _Log::log(_Log::$INFO,'sending request edit archivetype');
        $id   = $request->id;
        $name = $request->name;
        if (_Authorize::manage(Archive::class)) {
            if (Archivetype::all()->where('id', $id)->count() != 0) {
                $obj       = Archivetype::with(['officer'])->firstWhere('id', $id);
                $obj->name = $name;
                if ($obj->officer->id != _Authorize::data()->officer->id) {
                    $obj->officer()->detach($obj->officer->id);
                    $obj->officer()->associate(_Authorize::data()->officer);
                }
                $obj->save();

                $new = Archivetype::with(['officer','officer.user','archives','archives.archivefile','archives.officer','archives.officer.user'])->firstWhere('id', $obj->id);
                $status  = ['status'=>'success','message'=>'Berhasil merubah tipe arsip', 'archivetype'=>$new];
                _Log::log(_Log::$SUCCESS,'sending request edit archivetype success');
                _Activity::do('merubah tipe arsip ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Arsip '.$name.' tidak tersedia'];
                _Log::log(_Log::$DANGER,'sending request edit archivetype failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request edit archivetype failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteType(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete archivetype');
        $id   = $request->id;
        if (_Authorize::manage(Archive::class)) {
            if (Archivetype::all()->where('id', $id)->count() != 0) {
                $obj = Archivetype::all()->firstWhere('id', $id);
                $name = $obj->name;
                $obj->delete();

                $status  = ['status'=>'success','message'=>'Berhasil menghapus tipe arsip '.$name];
                _Log::log(_Log::$SUCCESS,'sending request delete archivetype success');
                _Activity::do('menghapus tipe arsip ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Arsip tidak tersedia'];
                _Log::log(_Log::$DANGER,'sending request delete archivetype failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request delete archivetype failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function clearType(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request clear archivetype');
        $id = $request->id;
        if (_Authorize::manage(Archive::class)) {
            if (Archivetype::all()->where('id', $id)->count() != 0) {
                $archives = \App\Models\Archive::all()->where('archivetype_id', $id);
                try {
                    foreach ($archives as $archive) {
                        $archive->delete();
                    }
                } catch (Exception $e) {}
                $type     = Archivetype::with(['archives','archives.archivefile','officer','officer.user','archives.officer','archives.officer.user'])->firstWhere('id', $id);

                $status  = ['status'=>'success','message'=>'Berhasil membersihkan tipe arsip '.$type->name,'archivetype'=>$type];
                _Log::log(_Log::$SUCCESS,'sending request clear archivetype success');
                _Activity::do('membersihkan tipe arsip ' . $type->name);
            } else {
                $status = ['status'=>'error','message'=>'Arsip tidak tersedia'];
                _Log::log(_Log::$WARNING,'sending request clear archivetype failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request clear archivetype failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function downloadType($token) {
        _Log::log(_Log::$INFO,'sending request download archives folder');
        if (Archivetype::all()->where('token', $token)->count() != 0 &&_Authorize::manage(Archive::class)) {
            $artype   = Archivetype::with(['archives','archives.archivefile','archives.user'])->where('token', $token)->first();
            $zip_name = $artype->name . date("_Y-m-d") . '.zip';
            $archives = $artype->archives()->get();
            $iter     = 1;
            $result   = [];
            foreach ($archives as $archive) {
                if ($archive->archivefile->enable_public) {
                    $name = '' . $iter . '_' . $archive->name . '_' . $archive->user->name . '.' . $archive->extension;
                    $result[$archive->path] = $name;
                    $iter++;
                }
            }
            _Log::log(_Log::$SUCCESS,'sending request download archives folder success');
            _Activity::do('mengunduh tipe arsip ' . $zip_name);
            return Zip::create($zip_name, $result);
        } else {
            _Log::log(_Log::$DANGER,'sending request download archives folder failed');
            return view('system.404');
        }
    }

    public function deleteArchive(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete archive');
        $id = $request->id;
        if (\App\Models\Archive::all()->where('id', $id)->count() != 0) {
            $arv = \App\Models\Archive::with('user')->firstWhere('id', $id);
            if (_Authorize::manage(Archive::class) || _Authorize::data()->id == $arv->user->id) {
                $name = $arv->name;
                try {
                    $arv->delete();
                } catch (Exception $e) {}
                $status  = ['status'=>'success','message'=>'Berhasil menghapus arsip '.$name];
                _Log::log(_Log::$SUCCESS,'sending request delete archive success');
                _Activity::do('menghapus arsip ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
                _Log::log(_Log::$DANGER,'sending request delete archive failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Arsip tidak tersedia'];
            _Log::log(_Log::$WARNING,'sending request delete archive failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function downloadArchive($token) {
        _Log::log(_Log::$INFO,'sending request download archive');
        if (\App\Models\Archive::all()->where('token', $token)->count() != 0) {
            $archive = \App\Models\Archive::with(['archivefile','user'])->firstWhere('token', $token);
            $header  = \App\Models\Archive::header($archive->extension);
            $name    = $archive->name . '_' . $archive->user->name . '.' . $archive->extension;
            $path    = public_path(preg_replace("/^public/m", 'storage', $archive->path));
            if ($archive->archivefile->enable_public) {
                _Log::log(_Log::$SUCCESS,'sending request download archive uccess');
                _Activity::do('mengunduh arsip ' . $name);
                return response()->download($path, $name, $header);
            } elseif (!$archive->archivefile->enable_public && _Authorize::login()) {
                if ($archive->user->id == _Authorize::data()->id) {
                    _Log::log(_Log::$SUCCESS,'sending request download archive success');
                    _Activity::do('mengunduh arsip ' . $name);
                    return response()->download($path, $name, $header);
                } else {
                    _Log::log(_Log::$DANGER,'sending request download archive failed');
                    return view('system.404');
                }
            } else {
                _Log::log(_Log::$DANGER,'sending request download archive failed');
                return view('system.404');
            }
        } else {
            _Log::log(_Log::$DANGER,'sending request download archive failed');
            return view('system.404');
        }
    }

}
