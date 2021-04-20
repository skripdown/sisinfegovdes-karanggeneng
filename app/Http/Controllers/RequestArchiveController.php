<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Archive;
use App\Models\Archivefile;
use App\Models\Archivetype;
use App\Models\Reqarchive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestArchiveController extends Controller
{
    public function requests($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Reqarchive::with($models)->get();
        }
        else {
            $data   = Reqarchive::all();
        }
        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Archive::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.request_archive"');
                _Activity::do('mengakses halaman permintaan arsip');
                _App::page('request_archives', $flag);
                return view('admin.request_archive',compact('data'));
            }
        }
        if (_Authorize::citizen() && $show) {
            _Log::log(_Log::$DANGER, 'sending get url failed on authorization error');
            return view('system.404');
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function makeRequest(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request make request archive');
        if (_Authorize::login()) {
            $type_id = $request->type;
            $name    = $request->name;
            $public  = $request->enable_public;
            if (Archivetype::all()->where('id', $type_id)->count() != 0) {
                $type   = Archivetype::all()->firstWhere('id', $type_id);
                $user   = _Authorize::data();
                $req    = new Reqarchive();
                $req->name  = $name;
                $req->token = Archivetype::makeToken(30);
                $req->type  = $type->code;
                $req->archivetype   = $type->id;
                $req->enable_public = $public;
                $req->user()->associate($user);
                $req->save();

                $status = ['status'=>'error','message'=>'Berhasil menambahkan permohonan ' . $name . ' pada sistem', 'reqarchive'=>$req];
                _Log::log(_Log::$SUCCESS,'sending request make request archive success');
                _Activity::do('mengajukan ' . $name . ' pada arsip ' . $type->name);
            } else {
                $status = ['status'=>'error','message'=>'Data tipe arsip tidak terdapat pada sistem'];
                _Log::log(_Log::$DANGER,'sending request make request archive failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan autentikasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request make request archive failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function upload(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request upload archive');
        $id   = $request->id;
        $file = $request->file('file');
        if (_Authorize::manage(Archive::class)) {
            if (Reqarchive::all()->where('id', $id)->count() != 0) {
                if (_Image::allowedDoc($file)) {
                    $officer = _Authorize::data()->officer;
                    $req     = Reqarchive::with(['user'])->firstWhere('id', $id);
                    $type    = Archivetype::all()->firstWhere('id', $req->archivetype);
                    $name    = $req->name;
                    $arv     = new \App\Models\Archive();
                    $arfile  = new Archivefile();

                    $arv->name  = $name;
                    $arv->token = $req->token;
                    $arv->path  = Archivetype::upload($type->code, $req->token, $file);
                    $arv->extension = $file->extension();
                    $arv->user()->associate($req->user);
                    $arv->archivetype()->associate($type);
                    $arv->officer()->associate($officer);
                    $arv->save();

                    $arfile->enable_public = $req->enable_public;
                    $arfile->link = url('/archive/'.$req->token);
                    $arfile->archive()->associate($arv);
                    $arfile->save();
                    try {
                        $req->delete();
                    } catch (\Exception $e) {
                    }
                    $status  = ['status'=>'success','message'=>'Berhasil menambahkan surat ' . $name . ' pada arsip ' . $type->name];
                    _Log::log(_Log::$SUCCESS,'sending request upload archive success');
                    _Activity::do('menambahkan surat ' . $name . ' pada arsip ' . $type->name);
                } else {
                    $status = ['status'=>'error','message'=>'extensi "' . $file->extension() . '" tidak diperbolehkan'];
                    _Log::log(_Log::$DANGER,'sending request upload archive failed');
                }
            } else {
                $status = ['status'=>'error','message'=>'Permohonan tidak tersedia'];
                _Log::log(_Log::$DANGER,'sending request upload archive failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request upload archive failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function delete(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete request archive');
        $id = $request->id;
        if (_Authorize::manage(Archive::class)) {
            if (Reqarchive::all()->where('id', $id)->count() != 0) {
                $req  = Reqarchive::all()->firstWhere('id', $id);
                $type = Archivetype::all()->firstWhere('id', $req->archivetype);
                $name = $req->name;
                $req->delete();

                $status  = ['status'=>'success','message'=>'Berhasil menghapus permintaan ' . $name . ' pada arsip ' . $type->name];
                _Log::log(_Log::$SUCCESS,'sending request delete request archive success');
                _Activity::do('menghapus permintaan ' . $name . ' pada arsip ' . $type->name);
            } else {
                $status = ['status'=>'error','message'=>'Permohonan tidak tersedia'];
                _Log::log(_Log::$DANGER,'sending request delete request archive failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pegawai'];
            _Log::log(_Log::$DANGER,'sending request delete request archive failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

}
