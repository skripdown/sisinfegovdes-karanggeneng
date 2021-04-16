<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_Authorize;
use App\Http\back\_Image;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Http\back\authorize\Account;
use App\Models\Citeducation;
use App\Models\Citoccupation;
use App\Models\Citreligion;
use App\Models\District;
use App\Models\Education;
use App\Models\Family;
use App\Models\Hamlet;
use App\Models\Neighboor;
use App\Models\Occupation;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        if (!_Authorize::login()) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return (object)null;
        }

        if (_UI::relation($flag)) {
            _Log::log(_Log::$DANGER, 'sending get url success');
            return User::with(_UI::relation_with($flag))->get();
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data akun');

        return User::all();
    }

    public function update(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request update data');
        if (_Authorize::login()) {
            $user        = User::all()->firstWhere('id', _Authorize::data()->id);
            $ctzn        = $user->citizen;
            if (Hash::check($request->old_password, $user->password)) {
                $user->name  = $request->name;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $ctzn->name  = $request->name;
                $ctzn->blood = $request->blood;
                if ($ctzn->citreligion->religion->id != $request->religion) {
                    $ctzrel  = $ctzn->citreligion;
                    $ctzrel->delete();
                    $ctzrel  = new Citreligion();
                    $ctzrel->citizen()->associate($ctzn);
                    $ctzrel->religion()->associate(Religion::all()->firstWhere('id', $request->religion));
                    $ctzrel->save();
                }
                if ($ctzn->citeducation->education->id != $request->education) {
                    $ctzrel  = $ctzn->citeducation;
                    $ctzrel->delete();
                    $ctzrel  = new Citeducation();
                    $ctzrel->citizen()->associate($ctzn);
                    $ctzrel->education()->associate(Education::all()->firstWhere('id', $request->education));
                    $ctzrel->save();
                }
                if ($ctzn->citoccupation->occupation->id != $request->occupation) {
                    $ctzrel  = $ctzn->citoccupation;
                    $ctzrel->delete();
                    $ctzrel  = new Citoccupation();
                    $ctzrel->citizen()->associate($ctzn);
                    $ctzrel->occupation()->associate(Occupation::all()->firstWhere('id', $request->occupation));
                    $ctzrel->save();
                }
                if ($request->file('profile') != null) {
                    if ($user->officer != null) {
                        $path  = 'admin_profile';
                        $role  = 'adm';
                        $admin = true;
                    }
                    else {
                        $path  = 'citizen_profile';
                        $role  = 'ctz';
                        $admin = false;
                    }
                    _Image::remove($user->pic, $path);
                    $pic       = _Image::setProfile($request->file('profile'), $user->identity, $role, $admin);
                    $user->pic = $pic;
                    $ctzn->pic = $pic;
                }
                if ($request->marriage == 'sudah')
                    $ctzn->marriage = true;
                else
                    $ctzn->marriage = false;

                $district_id = $request->district;
                $hamlet_id   = $request->hamlet;
                $neighbor_id = $request->neighbor;
                $family_num  = $request->family;
                if ($ctzn->district->id != $district_id) {
                    $ctzn->district()->detach($ctzn->district->id);
                    $ctzn->district()->associate(District::all()->firstWhere('id', $district_id));
                }
                if ($ctzn->hamlet->id != $hamlet_id) {
                    $ctzn->hamlet()->detach($ctzn->hamlet->id);
                    $ctzn->hamlet()->associate(Hamlet::all()->firstWhere('id', $hamlet_id));
                }
                if ($ctzn->neighboor->id != $neighbor_id) {
                    $ctzn->neighboor()->detach($ctzn->neighboor->id);
                    $ctzn->neighboor()->associate(Neighboor::all()->firstWhere('id', $neighbor_id));
                }
                if ($ctzn->family->number != $family_num) {
                    $old_fam = $ctzn->family;
                    $ctzn->family()->detach($ctzn->family->id);
                    if (Family::all()->where('number', $family_num)->count() == 0) {
                        $family   = new Family();
                        $district = District::all()->firstWhere('id', $district_id);
                        $hamlet   = Hamlet::all()->firstWhere('id', $hamlet_id);
                        $neighbor = Neighboor::all()->firstWhere('id', $neighbor_id);
                        $family->district()->associate($district);
                        $family->hamlet()->associate($hamlet);
                        $family->neighboor()->associate($neighbor);
                        $family->save();
                    }
                    else
                        $family = Family::all()->firstWhere('number', $family_num);
                    $ctzn->family()->associate($family);
                    if (count($old_fam->citizens()->get()) == 0)
                        $old_fam->delete();
                }

                if ($request->new_password != null)
                    $user->password = Hash::make($request->new_password);

                $user->save();
                $ctzn->save();

                $status = ['status'=>'success','message'=>'Data pengguna berhasil diperbahrui.'];
                _Log::log(_Log::$SUCCESS,'sending request update data success');
                _Activity::do('mengubah data akun');
            }
            else {
                $status = ['status'=>'error','message'=>'Kata sandi pengguna salah.'];
                _Log::log(_Log::$DANGER,'sending request update data failed');
            }
        }
        else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi. Pengguna belum masuk ke aplikasi'];
            _Log::log(_Log::$DANGER,'sending request update data failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function block(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request block user');
        if (_Authorize::manage(Account::class) || _Authorize::chief()) {
            $user = User::all()->firstWhere('id', $request->id);
            $user->usable = false;
            $user->save();

            $status = ['status'=>'success','message'=>'Pengguna berhasil diblokir.'];
            _Log::log(_Log::$SUCCESS,'sending request block user success');
            _Activity::do('memblokir akun '. $user->name.' ('.$user->identity.')');
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi'];
            _Log::log(_Log::$DANGER,'sending request block user failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }

    public function unblock(Request $request):JsonResponse {
        _Log::log(_Log::$INFO,'sending request unblock user');
        if (_Authorize::manage(Account::class) || _Authorize::chief()) {
            $user = User::all()->firstWhere('id', $request->id);
            $user->usable = true;
            $user->save();

            $status = ['status'=>'success','message'=>'Pemblokiran pengguna berhasil dibuka.'];
            _Log::log(_Log::$SUCCESS,'sending request unblock user success');
            _Activity::do('membuka blokir akun '. $user->name.' ('.$user->identity.')');
        } else {
            $status = ['status'=>'error','message'=>'Kesalahan otorisasi'];
            _Log::log(_Log::$DANGER,'sending request unblock user failed');
        }

        return response()->json(array_merge($request->all(), $status));
    }
}
