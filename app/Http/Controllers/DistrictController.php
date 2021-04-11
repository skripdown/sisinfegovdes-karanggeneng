<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function districts($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = District::with($models)->get();
        }
        else {
            $data   = District::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.district"');
                _Activity::do('mengakses halaman dusun');
                return view('admin.district',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.district"');
                _Activity::do('mengakses halaman dusun');
                return view('citizen.district',compact('data'));
            }
        }

        _Activity::do('mengakses data dusun');
        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function insertDistrict(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert district');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (District::all()->where('name', $name)->count() == 0) {
                $district = new District();
                $district->name = $name;
                $district->size = $request->size;
                $district->save();
                $status     = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data dusun', 'id'=>$district->id];
                _Log::log(_Log::$SUCCESS,'sending request insert district success');
                _Activity::do('menambahkan dusun ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data dusun'];
                _Log::log(_Log::$DANGER,'sending request insert district failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert district failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function editDistrict(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request edit district');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (District::all()->where('id', $id)->count() > 0) {
                $district   = District::all()->firstWhere('id', $id);
                $name       = $district->name;
                $district->name = $request->name;
                $district->size = $request->size;
                $district->save();
                $status     = ['status'=>'success','message'=>'Berhasil memperbahrui data dusun'];
                _Log::log(_Log::$SUCCESS,'sending request edit district success');
                _Activity::do('mengubah data dusun ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Data dusun tidak dapat ditemukan'];
                _Log::log(_Log::$DANGER,'sending request edit district failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request edit district failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteDistrict(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete district');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (District::all()->where('id', $id)->count() > 0) {
                $district = District::all()->firstWhere('id', $id);
                $name     = $district->name;
                $district->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data dusun'];
                _Log::log(_Log::$SUCCESS,'sending request delete district success');
                _Activity::do('menghapus data dusun ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Dusun tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete district failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete district failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
