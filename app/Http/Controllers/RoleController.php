<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Account;
use App\Models\Approval;
use App\Models\Modify;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function roles($flag='') {
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
            $data   = Role::with($models)->get();
        }
        else {
            $data   = Role::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Account::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.account"');
                _Activity::do('mengakses halaman hak akses');
                return view('admin.account',compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$DANGER,'sending get url failed with return "authorization error"');
                return 'authorization error';
            }
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        return $data;
    }

    public function update(Request $request): JsonResponse {
        _Log::log(_Log::$INFO,'sending request modify officer roles approval');
        if (_Authorize::manage(Account::class) || _Authorize::chief()) {
            $role = Role::all()->firstWhere('id',$request->id);
            if (!$role->chief) {
                $officer = $role->user->officer;
                $approval = new Approval();
                $approval->officer()->associate($officer);
                $approval->from = _Authorize::data()->name;
                $approval->type = 'acc';
                $approval->save();

                if ($request->account == 'iya') $mod_account = true;
                else $mod_account = false;
                if ($request->publication == 'iya') $mod_publication = true;
                else $mod_publication = false;
                if ($request->archive == 'iya') $mod_archive = true;
                else $mod_archive = false;
                if ($request->civil == 'iya') $mod_civil = true;
                else $mod_civil = false;
                if ($request->employee == 'iya') $mod_employee = true;
                else $mod_employee = false;
                if ($request->developer == 'iya') $mod_developer = true;
                else $mod_developer = false;

                if ($role->account != $mod_account) {
                    $tmp         = $mod_account;
                    $mod_account = new Modify();
                    $mod_account->type = 'bool';
                    $mod_account->attribute = 'account';
                    $mod_account->value     = $tmp . '';
                    $mod_account->approval()->associate($approval);
                    $mod_account->save();
                }
                if ($role->publication != $mod_publication) {
                    $tmp             = $mod_publication;
                    $mod_publication = new Modify();
                    $mod_publication->type = 'bool';
                    $mod_publication->attribute = 'publication';
                    $mod_publication->value     = $tmp . '';
                    $mod_publication->approval()->associate($approval);
                    $mod_publication->save();
                }
                if ($role->archive != $mod_archive) {
                    $tmp         = $mod_archive;
                    $mod_archive = new Modify();
                    $mod_archive->type = 'bool';
                    $mod_archive->attribute = 'archive';
                    $mod_archive->value     = $tmp . '';
                    $mod_archive->approval()->associate($approval);
                    $mod_archive->save();
                }
                if ($role->civil != $mod_civil) {
                    $tmp       = $mod_civil;
                    $mod_civil = new Modify();
                    $mod_civil->type = 'bool';
                    $mod_civil->attribute = 'civil';
                    $mod_civil->value     = $tmp . '';
                    $mod_civil->approval()->associate($approval);
                    $mod_civil->save();
                }
                if ($role->employee != $mod_employee) {
                    $tmp          = $mod_employee;
                    $mod_employee = new Modify();
                    $mod_employee->type = 'bool';
                    $mod_employee->attribute = 'employee';
                    $mod_employee->value     = $tmp . '';
                    $mod_employee->approval()->associate($approval);
                    $mod_employee->save();
                }
                if ($role->developer != $mod_developer) {
                    $tmp           = $mod_developer;
                    $mod_developer = new Modify();
                    $mod_developer->type = 'bool';
                    $mod_developer->attribute = 'developer';
                    $mod_developer->value     = $tmp . '';
                    $mod_developer->approval()->associate($approval);
                    $mod_developer->save();
                }
                $approval = Approval::with(['modifies'])->where('type', 'acc')->firstWhere('officer_id', $officer->id);

                $status  = ['status'=>'success','message'=>'Berhasil mengirim permintaan modifikasi akses pegawai', 'approval'=>$approval];
                _Log::log(_Log::$SUCCESS,'sending request officer authorization approval success');
                _Activity::do('mengirim persetujuan modifikasi akses pegawai ' . $officer->user->name);
            } else {
                $status = ['status'=>'error','message'=>'Kesalahan enkapsulasi data'];
                _Log::log(_Log::$DANGER,'sending request modify officer roles approval failed');
            }
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi akun pengguna'];
            _Log::log(_Log::$DANGER,'sending request modify officer roles approval failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
