<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\Religion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('g');
    }

    public function religions($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Religion::with($models)->get();
        }
        else {
            $data   = Religion::all();
        }

        if ($show) {
            _Log::log(_Log::$SUCCESS,'sending get url success with dump religions');
            _Activity::do('mengakses data agama');
            dd($data);
        }
        else {
            _Log::log(_Log::$SUCCESS,'sending get url success with return data');
            _Activity::do('mengakses data agama');
            return $data;
        }
    }

    public function insertReligion(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert religion');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (Religion::all()->where('name', $name)->count() == 0) {
                $religion = new Religion();
                $religion->name = $name;
                $religion->save();
                $status   = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data agama', 'religion'=>$religion];
                _Log::log(_Log::$SUCCESS,'sending request insert religion success');
                _Activity::do('memasukkan agama ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data agama'];
                _Log::log(_Log::$DANGER,'sending request insert religion failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert religion failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteReligion(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete religion');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Religion::all()->where('id', $id)->count() > 0) {
                $religion = Religion::all()->firstWhere('id', $id);
                $name     = $religion->name;
                $religion->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data agama', 'id'=>$id];
                _Log::log(_Log::$SUCCESS,'sending request delete religion success');
                _Activity::do('menghapus data agama ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Agama tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete religion failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete religion failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function educations($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Education::with($models)->get();
        }
        else {
            $data   = Education::all();
        }

        if ($show) {
            _Log::log(_Log::$SUCCESS,'sending get url success with dump educations');
            _Activity::do('mengakses data agama');
            dd($data);
        }
        else {
            _Log::log(_Log::$SUCCESS,'sending get url success with return data');
            _Activity::do('mengakses data agama');
            return $data;
        }
    }

    public function insertEducation(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert education');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (Education::all()->where('name', $name)->count() == 0) {
                $education = new Education();
                $education->name = $name;
                $education->save();
                $status    = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data pendidikan', 'education'=>$education];
                _Log::log(_Log::$SUCCESS,'sending request insert education success');
                _Activity::do('memasukkan pendidikan ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data pendidikan'];
                _Log::log(_Log::$DANGER,'sending request insert education failed');
                _Activity::do('mengakses data pendidikan');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert education failed');
            _Activity::do('mengakses data pendidikan');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteEducation(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete education');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Education::all()->where('id', $id)->count() > 0) {
                $education = Education::all()->firstWhere('id', $id);
                $name      = $education->name;
                $education->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data pendidikan', 'id'=>$id];
                _Log::log(_Log::$SUCCESS,'sending request delete education success');
                _Activity::do('menghapus data pendidikan ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Pendidikan tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete education failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete education failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function occupations($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Occupation::with($models)->get();
        }
        else {
            $data   = Occupation::all();
        }

        if ($show) {
            _Log::log(_Log::$SUCCESS,'sending get url success with dump occupations');
            _Activity::do('mengakses data pekerjaan');
            dd($data);
        }
        else {
            _Log::log(_Log::$SUCCESS,'sending get url success with return data');
            _Activity::do('mengakses data pekerjaan');
            return $data;
        }
    }

    public function insertOccupation(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request insert occupation');
        if (_Authorize::manage(Civil::class)) {
            $name = $request->name;
            if (Occupation::all()->where('name', $name)->count() == 0) {
                $occupation = new Occupation();
                $occupation->name = $name;
                $occupation->save();
                $status     = ['status'=>'success','message'=>'Berhasil menambahkan '.$name.' pada data pekerjaan', 'occupation'=>$occupation];
                _Log::log(_Log::$SUCCESS,'sending request insert occupation success');
                _Activity::do('memasukkan pekerjaan ' . $name);
            } else {
                $status = ['status'=>'error','message'=>$name.' tidak dapat ditambahkan karena sudah tersedia pada data pekerjaan'];
                _Log::log(_Log::$DANGER,'sending request insert occupation failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request insert occupation failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteOccupation(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete occupation');
        if (_Authorize::manage(Civil::class)) {
            $id = $request->id;
            if (Occupation::all()->where('id', $id)->count() > 0) {
                $occupation = Occupation::all()->firstWhere('id', $id);
                $name       = $occupation->name;
                $occupation->delete();
                $status   = ['status'=>'success','message'=>'Berhasil menghapus '.$name.' dari data pekerjaan', 'id'=>$id];
                _Log::log(_Log::$SUCCESS,'sending request delete occupation success');
                _Activity::do('menghapus data pekerjaan ' . $name);
            } else {
                $status = ['status'=>'error','message'=>'Pekerjaan tidak ditemukan pada data'];
                _Log::log(_Log::$DANGER,'sending request delete occupation failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete occupation failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
