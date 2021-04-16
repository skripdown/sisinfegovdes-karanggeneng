<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Employee;
use App\Models\Approval;
use App\Models\Modify;
use App\Models\Officer;
use App\Models\Requestmutate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function officers($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Officer::with($models)->where('regis', 'in')->get();
        }
        else {
            $data   = Officer::all()->where('regis', 'in');
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Employee::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.officer"');
                _Activity::do('mengakses halaman pegawai');
                _App::page('officers', $flag);
                return view('admin.officer',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.officer"');
                _Activity::do('mengakses halaman pegawai');
                return view('citizen.officer',compact('data'));
            }
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function editOfficer(Request $request): JsonResponse {
        _Log::log(_Log::$INFO,'sending request edit officer approval');
        if (_Authorize::manage(Employee::class) || _Authorize::data()->officer->id == $request->id) {
            $id = $request->id;
            if (Officer::all()->where('id', $id)->count() > 0) {
                $officer  = Officer::all()->firstWhere('id', $id);
                $s1 = $officer->status . $officer->rank . $officer->salary . $officer->identity;
                $s2 = $request->status . $request->rank . $request->salary . $request->identity;
                if ($s1 != $s2) {
                    $approval = new Approval();
                    $approval->type = 'mod';
                    $approval->from = _Authorize::data()->name;
                    $approval->officer()->associate($officer);
                    $approval->save();
                    if ($officer->status != $request->status) {
                        $m1 = new Modify();
                        $m1->attribute = 'status';
                        $m1->type      = 'str';
                        $m1->value     = $request->status.'';
                        $m1->approval()->associate($approval);
                        $m1->save();
                    }
                    if ($officer->rank != $request->rank) {
                        $m2 = new Modify();
                        $m2->attribute = 'rank';
                        $m2->type      = 'str';
                        $m2->value     = $request->rank.'';
                        $m2->approval()->associate($approval);
                        $m2->save();
                    }
                    if ($officer->salary != $request->status) {
                        $m3 = new Modify();
                        $m3->attribute = 'salary';
                        $m3->type      = 'big';
                        $m3->value     = $request->salary.'';
                        $m3->approval()->associate($approval);
                        $m3->save();
                    }
                    if ($officer->identity != $request->identity) {
                        $m4 = new Modify();
                        $m4->attribute = 'identity';
                        $m4->type      = 'str';
                        $m4->value     = $request->identity.'';
                        $m4->approval()->associate($approval);
                        $m4->save();
                    }
                    $status = ['status'=>'success','message'=>'Berhasil mengirim permintaan pembahruan data pegawai', 'approval'=>$approval];
                    _Log::log(_Log::$SUCCESS,'sending request edit officer approval success');
                    _Activity::do('mengirim persetujuan pengubahan data pegawai ' . $officer->user->name);
                } else {
                    $status = ['status'=>'error','message'=>'Tidak ada perubahan pada data'];
                    _Log::log(_Log::$DANGER,'sending request edit officer approval failed');
                }
            } else {
                $status = ['status'=>'error','message'=>'Data informasi pegawai tidak bisa ditemukan'];
                _Log::log(_Log::$DANGER,'sending request edit officer approval failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request edit officer approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function deleteOfficer(Request $request): JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete officer approval');
        if (_Authorize::manage(Employee::class) || _Authorize::data()->officer->id == $request->id) {
            $id = $request->id;
            if (Officer::all()->where('id', $id)->count() > 0) {
                $officer  = Officer::all()->firstWhere('id', $id);
                $approval = new Approval();
                $approval->type = 'del';
                $approval->from = _Authorize::data()->name;
                $approval->officer()->associate($officer);
                $approval->save();
                $attribute = new Modify();
                $attribute->attribute = 'id';
                $attribute->type = 'big';
                $attribute->value = $request->id.'';
                $attribute->approval()->associate($approval);
                $attribute->save();

                $status  = ['status'=>'success','message'=>'Berhasil mengirim permintaan penghapusan data pegawai', 'approval'=>$approval];
                _Log::log(_Log::$SUCCESS,'sending request delete officer approval success');
                _Activity::do('mengirim persetujuan penghapusan data pegawai ' . $officer->user->name);
            } else {
                $status = ['status'=>'error','message'=>'Data informasi pegawai tidak bisa ditemukan'];
                _Log::log(_Log::$DANGER,'sending request delete officer approval failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete officer approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function mutate(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request mutate officer approval');
        if ((_Authorize::login() && _Authorize::data()->id == $request->id) || _Authorize::manage(Employee::class)) {
            $id       = $request->id;
            $identity = $request->identity;
            $type     = $request->type;
            $user     = User::all()->firstWhere('id', $id);
            if ($type == 'in') {
                if (Officer::all()->where('identity', $identity)->count() == 0 && $user->officer == null) {
                    $officer  = _Authorize::data()->officer;
                    $name     = $request->name;
                    $salary   = $request->salary;
                    $status_  = $request->status_;
                    $rank     = $request->rank;

                    $mutate   = new Requestmutate();
                    $mutate->type = $type;
                    $mutate->officer()->associate($officer);
                    $mutate->user_target = $id;
                    if ($id == _Authorize::data()->id) {
                        $mutate->me = true;
                    }
                    $mutate->save();
                    $approval = new Approval();
                    $approval->type = $type;
                    $approval->mutation_target = $mutate->id;
                    $approval->from = _Authorize::data()->name;
                    $approval->officer()->associate($officer);
                    $approval->save();

                    $m1 = new Modify();
                    $m1->attribute = 'identity';
                    $m1->type = 'str';
                    $m1->value = $identity.'';
                    $m1->approval()->associate($approval);
                    $m1->save();
                    $m2 = new Modify();
                    $m2->attribute = 'status';
                    $m2->type = 'str';
                    $m2->value = $status_.'';
                    $m2->approval()->associate($approval);
                    $m2->save();
                    $m3 = new Modify();
                    $m3->attribute = 'rank';
                    $m3->type = 'str';
                    $m3->value = $rank.'';
                    $m3->approval()->associate($approval);
                    $m3->save();
                    $m4 = new Modify();
                    $m4->attribute = 'salary';
                    $m4->type = 'big';
                    $m4->value = $salary.'';
                    $m4->approval()->associate($approval);
                    $m4->save();
                    $m5 = new Modify();
                    $m5->attribute = 'id';
                    $m5->type = 'big';
                    $m5->value = $request->id.'';
                    $m5->approval()->associate($approval);
                    $m5->save();

                    $status  = ['status'=>'success','message'=>'Berhasil mengirim permintaan mutasi', 'requestmutate'=>$mutate, 'approval'=>$approval];
                    _Log::log(_Log::$SUCCESS,'sending request mutate officer approval success');
                    _Activity::do('mengirim persetujuan mutasi data pegawai ' . $name);
                } else {
                    $status = ['status'=>'error','message'=>'Kesalahan kredensi identitas pegawai'];
                    _Log::log(_Log::$DANGER,'sending request mutate officer approval failed');
                }
            } else {
                if ($user->officer != null) {
                    $officer = $user->officer;
                    $mutate = new Requestmutate();
                    $mutate->officer()->associate($officer);
                    $mutate->type = $type;
                    $mutate->user_target = $id;
                    if ($id == _Authorize::data()->id) {
                        $mutate->me = true;
                    }
                    $mutate->save();
                    $approval = new Approval();
                    $approval->type = $type;
                    $approval->mutation_target = $mutate->id;
                    $approval->from = _Authorize::data()->name;
                    $approval->officer()->associate($officer);
                    $approval->save();
                    $mod1 = new Modify();
                    $mod1->attribute = 'identity';
                    $mod1->value     = $officer->identity;
                    $mod1->type      = 'str';
                    $mod1->approval()->associate($approval);
                    $mod1->save();
                    $mod2 = new Modify();
                    $mod2->attribute = 'name';
                    $mod2->value     = $user->name;
                    $mod2->type      = 'str';
                    $mod2->approval()->associate($approval);
                    $mod2->save();
                    $mod3 = new Modify();
                    $mod3->attribute = 'rank';
                    $mod3->value     = $officer->rank;
                    $mod3->type      = 'str';
                    $mod3->approval()->associate($approval);
                    $mod3->save();
                    $mod4 = new Modify();
                    $mod4->attribute = 'status';
                    $mod4->value     = $officer->status;
                    $mod4->type      = 'str';
                    $mod4->approval()->associate($approval);
                    $mod4->save();
                    $mod5 = new Modify();
                    $mod5->attribute = 'timestamp_old';
                    $mod5->value     = $officer->created_at;
                    $mod5->type      = 'str';
                    $mod5->approval()->associate($approval);
                    $mod5->save();

                    $status  = ['status'=>'success','message'=>'Berhasil mengirim permintaan mutasi', 'requestmutate'=>$mutate, 'approval'=>$approval];
                    _Log::log(_Log::$SUCCESS,'sending request mutate officer approval success');
                    _Activity::do('mengirim persetujuan mutasi data pegawai ' . $officer->user->name);
                } else {
                    $status = ['status'=>'error','message'=>'Kesalahan kredensi identitas pegawai'];
                    _Log::log(_Log::$DANGER,'sending request mutate officer approval failed');
                }
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan autentikasi'];
            _Log::log(_Log::$DANGER,'sending request mutate officer approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function mutationIn($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Approval::with($models)->where('type', 'in')->get()->toJson();
        }
        else {
            $data   = Approval::all()->where('type', 'in')->toJson();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Employee::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.mutation_in"');
                _Activity::do('mengakses halaman mutasi masuk');
                _App::page('mutation_ins', $flag);
                return view('admin.mutation_in',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            _Log::log(_Log::$DANGER,'sending get url failed on authorization');
            if ($show)
                return view('system.404');
            return (object)null;
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data mutasi masuk');
        return $data;
    }

    public function mutationOut($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Requestmutate::with($models)->where('type', 'out')->orWhere('type', 'exp')->get()->toJson();
        }
        else {
            $data   = Requestmutate::with([])->where('type', 'out')->orWhere('type', 'exp')->get()->toJson();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Employee::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.mutation_out"');
                _Activity::do('mengakses halaman mutasi keluar');
                _App::page('mutation_outs', $flag);
                return view('admin.mutation_out',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            _Log::log(_Log::$DANGER,'sending get url failed on authorization');
            if ($show)
                return view('system.404');
            return (object)null;
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data mutasi keluar');
        return $data;
    }

    public function expires($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Requestmutate::with($models)->where('type', 'out')->orWhere('type', 'exp')->get()->toJson();
        }
        else {
            $data   = Requestmutate::with([])->where('type', 'out')->orWhere('type', 'exp')->get()->toJson();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Employee::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.expire"');
                _Activity::do('mengakses halaman pensiun');
                _App::page('expires', $flag);
                return view('admin.expire',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            _Log::log(_Log::$DANGER,'sending get url failed on authorization');
            if ($show)
                return view('system.404');
            return (object)null;
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data pensiun');
        return $data;
    }
}
