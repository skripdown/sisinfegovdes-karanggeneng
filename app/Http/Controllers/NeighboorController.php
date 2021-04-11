<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\District;
use App\Models\Hamlet;
use App\Models\Neighboor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NeighboorController extends Controller
{
    public function neighboors($flag='') {
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
            $data   = Neighboor::with($models)->get();
        }
        else {
            $data   = Neighboor::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.neighboor"');
                _Activity::do('mengakses halaman RW');
                return view('admin.neighboor',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.neighboor"');
                _Activity::do('mengakses halaman RW');
                return view('citizen.neighboor',compact('data'));
            }
        }

        _Activity::do('mengakses data RW');
        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function insertNeighbor(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert neighboor');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (Neighboor::all()->where('name', $name)->where('hamlet_id', $request->hamlet_id)->count() == 0) {
                $district = District::all()->firstWhere('id', $request->district_id);
                $hamlet   = Hamlet::all()->firstWhere('id', $request->hamlet_id);
                $neighbor = new Neighboor();
                $neighbor->name = $name;
                $neighbor->district()->associate($district);
                $neighbor->hamlet()->associate($hamlet);
                $neighbor->save();

                $status     = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data RW', 'hamlet'=>$hamlet, 'district'=>$district, 'neighbor'=>$neighbor];
                _Log::log(_Log::$SUCCESS,'sending request insert neighboor success');
                _Activity::do('menambahkan RW ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data RW'];
                _Log::log(_Log::$DANGER,'sending request insert neighboor failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert neighboor failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function editNeighbor(Request $request): JsonResponse {
        _Log::log(_Log::$INFO,'sending request edit neighboor');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Neighboor::all()->where('id', $id)->count() > 0) {
                $neighbor       = Neighboor::all()->firstWhere('id', $id);
                $name           = $neighbor->name;
                $neighbor->name = $request->name;
                $new_district   = District::all()->firstWhere('id', $request->district_id);
                $new_hamlet     = Hamlet::all()->firstWhere('id', $request->hamlet_id);
                if ($neighbor->district->id != $request->district_id) {
                    $old_district = $neighbor->district;
                    $neighbor->district()->detach($old_district->id);
                    $neighbor->district()->associate($new_district);
                }
                if ($neighbor->hamlet->id != $request->hamler_id) {
                    $old_hamlet = $neighbor->hamlet;
                    $neighbor->hamlet()->detach($old_hamlet);
                    $neighbor->hamlet()->associate($new_hamlet);
                }
                $neighbor->save();
                $status = ['status'=>'success','message'=>'Berhasil memperbahrui data RT', 'neighbor'=>$neighbor, 'hamlet'=>$new_hamlet, 'district'=>$new_district];
                _Log::log(_Log::$SUCCESS,'sending request edit neighboor success');
                _Activity::do('mengubah data dusun ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Data RW tidak dapat ditemukan'];
                _Log::log(_Log::$DANGER,'sending request edit neighboor failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request edit neighboor failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteNeighbor(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete neighboor');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Neighboor::all()->where('id',$id)->count() > 0) {
                $neighbor = Neighboor::all()->firstWhere('id', $id);
                $name     = $neighbor->name;
                $neighbor->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data RW'];
                _Log::log(_Log::$SUCCESS,'sending request delete neighboor success');
                _Activity::do('menghapus data dusun ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'RW tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete neighboor failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete neighboor failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
