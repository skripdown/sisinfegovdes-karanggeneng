<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\District;
use App\Models\Hamlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HamletController extends Controller
{
    public function hamlets($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login()) {
            if ($show) {
                _Log::log(_Log::$WARNING, 'sending canceled on login required');
                return view('guest.login');
            }
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Hamlet::with($models)->get();
        }
        else {
            $data   = Hamlet::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Activity::do('mengakses halaman RT');
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.hamlet"');
                _App::page('hamlets', $flag);
                return view('admin.hamlet',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Activity::do('mengakses halaman RT');
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.hamlet"');
                return view('citizen.hamlet',compact('data'));
            }
        }

        _Activity::do('mengakses data RT');
        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function insertHamlet(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert hamlet');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (Hamlet::all()->where('name', $name)->where('district_id', $request->district_id)->count() == 0) {
                $district = District::all()->firstWhere('id', $request->district_id);
                $hamlet   = new Hamlet();
                $hamlet->name = $name;
                $hamlet->district()->associate($district);
                $hamlet->save();
                $status     = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data RT', 'hamlet'=>$hamlet, 'district'=>$district];
                _Log::log(_Log::$SUCCESS,'sending request insert hamlet success');
                _Activity::do('menambahkan RT ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data RT'];
                _Log::log(_Log::$DANGER,'sending request insert hamlet failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert hamlet failed');

        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function editHamlet(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request edit hamlet');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Hamlet::all()->where('id', $id)->count() > 0) {
                $hamlet = Hamlet::all()->firstWhere('id', $id);
                $name   = $hamlet->name;
                $hamlet->name = $request->name;
                $new_district = District::all()->firstWhere('id', $request->district_id);
                if ($hamlet->district->id != $request->district_id) {
                    $old_district = $hamlet->district;
                    $hamlet->district()->detach($old_district->id);
                    $hamlet->district()->associate($new_district);
                }
                $hamlet->save();
                $status     = ['status'=>'success','message'=>'Berhasil memperbahrui data RT', 'hamlet'=>$hamlet, 'district'=>$new_district];
                _Log::log(_Log::$SUCCESS,'sending request edit hamlet success');
                _Activity::do('mengubah data RT ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Data RT tidak dapat ditemukan'];
                _Log::log(_Log::$DANGER,'sending request edit hamlet failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request edit hamlet failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteHamlet(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete hamlet');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Hamlet::all()->where('id', $id)->count() > 0) {
                $hamlet = Hamlet::all()->firstWhere('id', $id);
                $name   = $hamlet->name;
                $hamlet->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data RT'];
                _Log::log(_Log::$SUCCESS,'sending request delete hamlet success');
                _Activity::do('menghapus data RT ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'RT tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete hamlet failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete hamlet failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
