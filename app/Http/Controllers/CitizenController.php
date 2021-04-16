<?php /** @noinspection SpellCheckingInspection */

namespace App\Http\Controllers;

use App\Http\back\_Activity;
use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Datetime;
use App\Http\back\_Log;
use App\Http\back\_Report;
use App\Http\back\_UI;
use App\Http\back\authorize\Civil;
use App\Models\Citizen;
use Illuminate\Http\Request;

class CitizenController extends Controller
{

    public function citizens($flag='') {
        _Log::log(_Log::$INFO, 'sending get url with param "'.$flag.'"');
        $show = _UI::show($flag);
        $rels = _UI::relation($flag);

        if (!_Authorize::login() && $show) {
            _Log::log(_Log::$WARNING, 'sending canceled on login required');
            return view('guest.login');
        }

        if ($rels) {
            $models = _UI::relation_with($flag);
            $data   = Citizen::with($models)->get();
        }
        else {
            $data   = Citizen::all();
        }

        if (_Authorize::admin()) {
            if ($show && _Authorize::manage(Civil::class)) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "admin.citizen"');
                _Activity::do('mengakses halaman kependudukan');
                _App::page('citizens', $flag);
                return view('admin.citizen', compact('data'));
            }
        }
        if (_Authorize::citizen()) {
            if ($show) {
                _Log::log(_Log::$SUCCESS,'sending get url success with return "citizen.citizen"');
                _Activity::do('mengakses halaman kependudukan');
                return view('citizen.citizen', compact('data'));
            }
        }

        _Log::log(_Log::$SUCCESS,'sending get url success with return data');
        _Activity::do('mengakses data penduduk');
        return $data;
    }

    public function reportCitizen():string {
        _Log::log(_Log::$INFO, 'sending get url');
        if (!_Authorize::login()) {
            _Log::log(_Log::$DANGER, 'sending get url failed response not logged in');
            return 'Kesalahan autentikasi pengguna';
        }
        if (_Authorize::manage(Civil::class)) {
            $data  = Citizen::with(['citeducation','citreligion','citoccupation','citeducation.education','citreligion.religion','citoccupation.occupation'])->get();
            $param = [
                'head' => [
                    ['label'=>'NIK','data'=>function($item){return '&nbsp;'.$item->identity;}],
                    ['label'=>'Nama','data'=>function($item){return '&nbsp;'.$item->name;}],
                    ['label'=>'Gender','data'=>function($item){return '&nbsp;'.$item->gender;}],
                    ['label'=>'Umur','data'=>function($item){return '&nbsp;'._Datetime::setAge($item->year_birth, $item->month_birth, $item->day_birth);}],
                    ['label'=>'Gol.darah','data'=>function($item){return '&nbsp;'.$item->blood;}],
                    ['label'=>'Pendidikan','data'=>function($item){return '&nbsp;'.$item->citeducation->education->name;}],
                    ['label'=>'Pekerjaan','data'=>function($item){return '&nbsp;'.$item->citoccupation->occupation->name;}],
                    ['label'=>'Agama','data'=>function($item){return '&nbsp;'.$item->citreligion->religion->name;}],
                ],
                'data' => $data
            ];
            _Log::log(_Log::$SUCCESS, 'sending get url success');
            _Activity::do('mengakses laporan data penduduk');
            return _Report::print('Data Penduduk', 'Laporan Data Kependudukan', $param);
        }
        _Log::log(_Log::$DANGER, 'sending get url failed response wrong credentials');
        return 'Kesalahan otorisasi akses pengguna';
    }

}
