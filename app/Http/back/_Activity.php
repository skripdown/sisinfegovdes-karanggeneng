<?php
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

/** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\back;


use App\Http\back\authorize\Developer;
use App\Models\Activity;
use App\Models\User;

class _Activity
{
    public static function do($activity='') {
        if (_Authorize::login()) {
            $user = _Authorize::data();
            $act  = new Activity();
            $act->activity = $activity;
            $act->user()->associate($user);
            $act->save();
        }
    }

    public static function getMy() {
        _Log::log(_Log::$INFO, 'get activities data');
        if (_Authorize::login()) {
            $activities = _Authorize::data()->activities()->get();
            _Log::log(_Log::$SUCCESS, 'get activites data success');
        } else {
            _Log::log(_Log::$DANGER, 'get activites data failed');
            $activities = [];
        }

        return $activities;
    }

    public static function getDev() {
        _Log::log(_Log::$INFO, 'get activities data');
        if (_Authorize::manage(Developer::class) || _Authorize::chief()) {
            $activities = User::with(['activities','officer'])->get();
            _Log::log(_Log::$INFO, 'get activites data success');
        } else {
            _Log::log(_Log::$SUCCESS, 'get activites data failed');
            $activities = [];
        }

        return $activities;
    }

    public static function delete($request):array
    {
        _Log::log(_Log::$INFO, 'clearing activities history');
        if (_Authorize::login()) {
            $activity_id = $request->id;
            $user_id     = _Authorize::data()->id;
            if (Activity::all()->where('id', $activity_id)->where('user_id', $user_id)->count() > 0) {
                $activity = Activity::all()->where('id', $activity_id)->firstWhere('user_id', $user_id);
                if ($activity->view_by_dev == false)
                    $activity->delete();
                else {
                    $activity->view_by_me = false;
                    $activity->save();
                }
                _Log::log(_Log::$SUCCESS, 'clearing activities history success');
                $status = ['status'=>'success','message'=>'berhasil menghapus riwayat aktivitas'];
            } else {
                _Log::log(_Log::$WARNING, 'no activities found');
                $status = ['status'=>'error','message'=>'tidak berhasil menghapus riwayat aktivitas'];
            }
        } else {
            _Log::log(_Log::$DANGER, 'clearing activities history failed');
            $status = ['status'=>'error','message'=>'terjadi kesalahan dalam menghapus riwayat aktivitas'];
        }

        return array_merge($request->all(), $status);
    }

    public static function meClear($request): array
    {
        _Log::log(_Log::$INFO, 'clearing activities history');
        if (_Authorize::login()) {
            $id         = _Authorize::data()->id;
            if (Activity::all()->where('view_by_me',true)->where('user_id',$id)->count() != 0) {
                $activities = Activity::all()->where('view_by_me',true)->where('user_id',$id);
                try {
                    foreach ($activities as $activity) {
                        $activity->view_by_me = false;
                        $activity->save();
                        if (!$activity->view_by_me && !$activity->view_by_dev)
                            $activity->delete();
                    }
                } catch (\Exception $e) {
                    _Log::log(_Log::$DANGER, 'clearing activities history failed');
                    _Log::system(_Log::$DANGER, 'system failed to clear user activities');
                    $status     = ['status'=>'error','message'=>'terjadi kesalahan dalam menghapus riwayat aktivitas'];
                    return array_merge($request->all(), $status);
                }
                _Log::log(_Log::$SUCCESS, 'clearing activities history success');
                $status = ['status'=>'success','message'=>'berhasil menghapus riwayat aktivitas'];
            }
            else {
                _Log::log(_Log::$WARNING, 'no activities found');
                $status = ['status'=>'error','message'=>'tidak berhasil menghapus riwayat aktivitas'];
            }
        }
        else {
            _Log::log(_Log::$DANGER, 'clearing activities history failed');
            $status = ['status'=>'error','message'=>'terjadi kesalahan dalam menghapus riwayat aktivitas'];
        }

        return array_merge($request->all(), $status);
    }

    public static function devClear($request):array {
        _Log::log(_Log::$INFO, 'clearing activities history');
        if (_Authorize::login() && _Authorize::manage(Developer::class)) {
            if (Activity::all()->where('view_by_dev', true)->count() != 0) {
                $activities = Activity::all()->where('view_by_dev',true);
                try {
                    foreach ($activities as $activity) {
                        if (!$activity->view_by_me)
                            $activity->delete();
                        else {
                            $activity->view_by_dev = false;
                            $activity->save();
                        }
                    }
                } catch (\Exception $e) {
                    _Log::log(_Log::$DANGER, 'clearing activities history failed');
                    _Log::system(_Log::$DANGER, 'system failed to clear user activities');
                    $status     = ['status'=>'error','message'=>'terjadi kesalahan dalam menghapus riwayat aktivitas'];
                    return array_merge($request->all(), $status);
                }
                _Log::log(_Log::$SUCCESS, 'clearing activities history success');
                $status = ['status'=>'success','message'=>'berhasil menghapus riwayat aktivitas'];
            } else {
                _Log::log(_Log::$WARNING, 'no activities found');
                $status = ['status'=>'error','message'=>'tidak berhasil menghapus riwayat aktivitas'];
            }
        } else {
            _Log::log(_Log::$DANGER, 'clearing activities history failed');
            $status = ['status'=>'error','message'=>'terjadi kesalahan dalam menghapus riwayat aktivitas'];
        }

        return array_merge($request->all(), $status);
    }

    public static function clearAll($request):array {
        _Log::log(_Log::$INFO, 'clearing all activities history');
        if (_Authorize::login() && _Authorize::manage(Developer::class)) {
            if (Activity::all()->where('view_by_dev', true)->count() != 0) {
                $activities = Activity::all()->where('view_by_dev', true);
                try {
                    foreach ($activities as $activity) {
                        $activity->view_by_dev = false;
                        $activity->save();
                        if (!$activity->view_by_me && !$activity->view_by_dev)
                            $activity->delete();
                    }
                } catch (\Exception $e) {
                    _Log::log(_Log::$DANGER, 'clearing activities history failed');
                    _Log::system(_Log::$DANGER, 'system failed to clear all activities');
                    $status     = ['status'=>'error','message'=>'terjadi kesalahan dalam membersihkan semua riwayat aktivitas'];
                    return array_merge($request->all(), $status);
                }
                _Log::log(_Log::$SUCCESS, 'clearing activities history success');
                $status = ['status'=>'success','message'=>'berhasil membersihkan semua riwayat aktivitas'];
            } else {
                _Log::log(_Log::$WARNING, 'no activities found');
                $status = ['status'=>'success','message'=>'berhasil membersihkan semua riwayat aktivitas'];
            }
        } else {
            _Log::log(_Log::$DANGER, 'clearing activities history failed');
            $status = ['status'=>'error','message'=>'terjadi kesalahan dalam membersihkan semua riwayat aktivitas'];
        }

        return array_merge($request->all(), $status);
    }
}
