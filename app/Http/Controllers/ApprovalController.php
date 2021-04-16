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
use App\Models\Approval;
use App\Models\Citoccupation;
use App\Models\Modify;
use App\Models\Occupation;
use App\Models\Officer;
use App\Models\Requestmutate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function approvals($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Approval::with($models)->get();
        }
        else {
            $data   = Approval::all();
        }

        if ($show && _Authorize::chief()) {
            _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.approval"');
            _Activity::do('mengakses halaman pengajuan persetujuan');
            _App::page('approvals', $flag);
            return view('admin.approval', compact('data'));
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$DANGER,'sending get url failed with return "system.404"');
                return view('system.404');
            }
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data pengajuan persetujuan');
        return $data;
    }

    public function delete(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request delete approval');
        if (_Authorize::chief()) {
            if (Approval::all()->where('id', $request->id)->count() > 0) {
                $approval = Approval::all()->firstWhere('id', $request->id);
                if ($approval->mutation_target != null) {
                    $mutation = Requestmutate::all()->firstWhere('id', $approval->mutation_target);
                    $mutation->delete();
                }
                $approval->delete();
                $status  = ['status'=>'success','message'=>'Berhasil menghapus permintaan persetujuan'];
                _Log::log(_Log::$SUCCESS,'sending request delete approval success');
                _Activity::do('menghapus permintaan persetujuan');
            } else {
                $status = ['status'=>'error','message'=>'Data permintaan tidak bisa ditemukan'];
                _Log::log(_Log::$DANGER,'sending request delete approval failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request delete approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function verify(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request verify approval');
        if (_Authorize::chief()) {
            if (Approval::all()->where('id', $request->id)->count() > 0) {
                $approval = Approval::all()->firstWhere('id', $request->id);
                $type     = $approval->type;
                if ($type == 'in') {
                    $mutation = Requestmutate::all()->firstWhere('id', $approval->mutation_target);
                    $user     = User::with(['citizen','citizen.citoccupation','citizen.citoccupation.occupation'])->firstWhere('id', $mutation->user_target);
                    $mod_id   = Modify::all()->where('attribute', 'identity')->firstWhere('approval_id', $approval->id);
                    $mod_rank = Modify::all()->where('attribute', 'rank')->firstWhere('approval_id', $approval->id);
                    $mod_stat = Modify::all()->where('attribute', 'status')->firstWhere('approval_id', $approval->id);
                    $mod_sal  = Modify::all()->where('attribute', 'salary')->firstWhere('approval_id', $approval->id);
                    $officer  = new Officer();
                    $officer->user()->associate($user);
                    $officer->identity   = $mod_id->value;
                    $officer->status     = $mod_stat->value;
                    $officer->rank       = $mod_rank->value;
                    $officer->set        = Officer::$rank[$mod_rank->value]['set'];
                    $officer->room       = Officer::$rank[$mod_rank->value]['room'];
                    $officer->occupation = 'admin';
                    $officer->salary     = (int)$mod_sal->value;
                    $officer->regis      = 'in';
                    $officer->save();
                    $role = new Role();
                    $role->admin = true;
                    $role->user()->associate($user);
                    $role->save();
                    $mutation->delete();
                    _Image::move($user->pic, 'citizen_profile', 'admin_profile');

                    $citizen = $user->citizen;
                    $occup   = new Citoccupation();
                    $citizen->citoccupation->delete();
                    if ($mod_stat == 'asn')
                        $occupation = Occupation::all()->firstWhere('name', 'ASN');
                    else
                        $occupation = Occupation::all()->firstWhere('name', 'Honorer');
                    $occup->citizen()->associate($citizen);
                    $occup->occupation()->associate($occupation);
                    $occup->save();
                } else if ($type == 'out' || $type == 'exp') {
                    $mutation = Requestmutate::all()->firstWhere('id', $approval->mutation_target);
                    $user     = User::with(['citizen','citizen.citoccupation','citizen.citoccupation.occupation'])->firstWhere('id', $mutation->user_target);
                    $officer  = $user->officer;
                    $role     = $user->role;
                    $officer->regis = $type;
                    $officer->save();
                    $role->delete();
                    $mutation->delete();
                    if ($type == 'exp') {
                        $citizen    = $user->citizen;
                        $occup      = new Citoccupation();
                        $occupation = Occupation::all()->firstWhere('name', 'Tidak Ada');
                        $occup->citizen()->associate($citizen);
                        $occup->occupation()->associate($occupation);
                        $citizen->citoccupation->delete();
                    }
                    _Image::move($user->pic, 'admin_profile', 'citizen_profile');
                } else if ($type == 'mod') {
                    $officer  = $approval->officer;
                    if (Modify::all()->where('attribute', 'identity')->where('approval_id', $approval->id)->count() > 0) {
                        $mod_id   = Modify::all()->where('attribute', 'identity')->firstWhere('approval_id', $approval->id);
                        $officer->identity   = $mod_id->value;
                    }
                    if (Modify::all()->where('attribute', 'rank')->where('approval_id', $approval->id)->count() > 0) {
                        $mod_rank = Modify::all()->where('attribute', 'rank')->firstWhere('approval_id', $approval->id);
                        $officer->rank       = $mod_rank->value;
                        $officer->set        = Officer::$rank[$mod_rank->value]['set'];
                        $officer->room       = Officer::$rank[$mod_rank->value]['room'];
                    }
                    if (Modify::all()->where('attribute', 'status')->where('approval_id', $approval->id)->count() > 0) {
                        $mod_stat = Modify::all()->where('attribute', 'status')->firstWhere('approval_id', $approval->id);
                        $officer->status     = $mod_stat->value;
                    }
                    if (Modify::all()->where('attribute', 'salary')->where('approval_id', $approval->id)->count() > 0) {
                        $mod_sal  = Modify::all()->where('attribute', 'salary')->firstWhere('approval_id', $approval->id);
                        $officer->salary     = (int)$mod_sal->value;
                    }
                    $officer->save();
                }
                else if ($type == 'del') {
                    $officer = $approval->officer;
                    $user     = User::with(['citizen','citizen.citoccupation','citizen.citoccupation.occupation','role'])->firstWhere('id', $officer->user->id);
                    $role    = $user->role;
                    $officer->delete();
                    $role->delete();
                    _Image::move($user->pic, 'admin_profile', 'citizen_profile');
                    $citizen    = $user->citizen;
                    $occup      = new Citoccupation();
                    $occupation = Occupation::all()->firstWhere('name', 'Tidak Ada');
                    $occup->citizen()->associate($citizen);
                    $occup->occupation()->associate($occupation);
                    $citizen->citoccupation->delete();
                }
                else if ($type == 'acc') {
                    $role = $approval->officer->user->role;
                    $modifies = $approval->modifies()->get();
                    foreach ($modifies as $modify) {
                        if ($modify->value == '1')
                            $val = true;
                        else
                            $val = false;
                        $key     = $modify->attribute;
                        $role->$key = $val;
                    }
                    $role->save();

                }
                $approval->delete();

                $status   = ['status'=>'success','message'=>'Berhasil menyetujui permintaan'];
                _Log::log(_Log::$SUCCESS,'sending request verify approval success');
                _Activity::do('menyetujui permintaan');
            } else {
                $status = ['status'=>'error','message'=>'Data permintaan tidak bisa ditemukan'];
                _Log::log(_Log::$DANGER,'sending request verify approval failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request verify approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
