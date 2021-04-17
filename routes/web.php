<?php /** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpUndefinedClassInspection */

use App\Http\back\_App;
use App\Http\back\_Authorize;
use App\Http\back\_Log;
use App\Http\back\_UI;
use App\Models\Citizen;
use App\Models\Religion;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\User;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::domain('{link}.'.env('APP_DOMAIN'))->group(function () {
    Route::get('/', 'SystemController@route');
    Route::get('/away',function (){
        _Log::log(_Log::$INFO,'sending get url redirect away');
        $domain  = env('APP_DOMAIN');
        if (env('ENABLE_SSL') == 'TRUE')
            $ptc = 'https://';
        else
            $ptc = 'http://';
        if (env('APP_DEBUG'))
            $domain = $ptc.$domain.':'.env('APP_PORT');
        else
            $domain = $ptc.$domain;
        return redirect()->away($domain);
    });
});

Route::get('/',function (){
    _Log::log(_Log::$INFO,'sending get url return "guest.landing"');
    return view('guest.landing');
})->name('landing');

Route::get('/away/{system}',function ($system='') {
    _Log::log(_Log::$INFO,'sending get url redirect away');
    $domain  = env('APP_DOMAIN');
    if ($system != '')
        $system = $system.'.';
    if (env('ENABLE_SSL') == 'TRUE')
        $ptc = 'https://';
    else
        $ptc = 'http://';
    if (env('APP_DEBUG'))
        $domain = $ptc.$system.$domain.':'.env('APP_PORT');
    else
        $domain = $ptc.$system.$domain;
    return redirect()->away($domain);
});

Route::get('/login', function () {
    _Log::log(_Log::$INFO,'sending get url login');
    if (!_Authorize::login()) {
        _Log::log(_Log::$SUCCESS,'sending get url login success return "guest.login"');
        return view('guest.login');
    }
    _Log::log(_Log::$WARNING,'sending get url login redirect to dashboard');
    return redirect()->route('dashboard');
})->name('login');
Route::get('/register', function () {
    _Log::log(_Log::$INFO,'sending get url registration');
    if (!_Authorize::login()) {
        _Log::log(_Log::$SUCCESS,'sending get url login success return "system.registration"');
        return view('system.registration');
    }
    _Log::log(_Log::$DANGER,'sending get url login redirect to dashboard');
    return redirect()->route('dashboard');
})->name('register');
Route::get('/dashboard','Controller@dashboard')->name('dashboard');

Route::post('userUpdate','UserController@update');
Route::post('userBlock', 'UserController@block');
Route::post('userUnblock', 'UserController@unblock');

Route::get('/roles{flag}','RoleController@roles')->name('roles');
Route::get('/roles',function (){return redirect()->route('roles', [_UI::$FLAG_UI]);});
Route::post('roleUpdate', 'RoleController@update');

Route::get('/albums{flag}','AlbumController@albums')->name('albums');
Route::get('/albums',function (){return redirect()->route('albums', [_UI::$FLAG_UI]);});
Route::get('/album{flag}','AlbumController@album')->name('album');
Route::get('/album',function (){return redirect()->route('album', [_UI::$FLAG_UI]);});

Route::get('/photos{flag}','PhotoController@photos')->name('photos');
Route::get('/photos',function (){return redirect()->route('photos', [_UI::$FLAG_UI]);});

Route::get('/blogs{flag}','BlogController@blogs')->name('blogs');
Route::get('/blogs',function (){return redirect()->route('blogs', [_UI::$FLAG_UI]);});
Route::get('/blog{flag}','BlogController@blog')->name('blog');
Route::get('/blog',function (){return redirect()->route('blog', [_UI::$FLAG_UI]);});

Route::get('/districts{flag}','DistrictController@districts')->name('districts');
Route::get('/districts',function (){return redirect()->route('districts', [_UI::$FLAG_UI]);});
Route::get('/districtReport','DistrictController@reportDistrict');
Route::post('districtInsert', 'DistrictController@insertDistrict');
Route::post('districtEdit', 'DistrictController@editDistrict');
Route::post('districtDelete', 'DistrictController@deleteDistrict');

Route::get('/hamlets{flag}','HamletController@hamlets')->name('hamlets');
Route::get('/hamlets',function (){return redirect()->route('hamlets', [_UI::$FLAG_UI]);});
Route::get('/hamletReport','HamletController@reportHamlet');
Route::post('hamletInsert', 'HamletController@insertHamlet');
Route::post('hamletEdit', 'HamletController@editHamlet');
Route::post('hamletDelete', 'HamletController@deleteHamlet');

Route::get('/neighboors{flag}','NeighboorController@neighboors')->name('neighboors');
Route::get('/neighboors',function (){return redirect()->route('neighboors', [_UI::$FLAG_UI]);});
Route::get('/neighboorReport','NeighboorController@reportNeighboor');
Route::post('neighborInsert', 'NeighboorController@insertNeighbor');
Route::post('neighborEdit', 'NeighboorController@editNeighbor');
Route::post('neighborDelete', 'NeighboorController@deleteNeighbor');

Route::get('/families{flag}','FamilyController@families')->name('families');
Route::get('/families',function (){return redirect()->route('families', [_UI::$FLAG_UI]);});
Route::get('/familieReport','FamilyController@reportFamily');

Route::get('/citizens{flag}','CitizenController@citizens')->name('citizens');
Route::get('/citizens',function (){return redirect()->route('citizens', [_UI::$FLAG_UI]);});
Route::get('/citizenReport','CitizenController@reportCitizen');

Route::get('/officers{flag}','OfficerController@officers')->name('officers');
Route::get('/officers',function (){return redirect()->route('officers', [_UI::$FLAG_UI]);});
Route::post('officerMutate', 'OfficerController@mutate');
Route::post('officerEdit', 'OfficerController@editOfficer');
Route::post('officerDelete', 'OfficerController@deleteOfficer');

Route::get('/mutins{flag}','OfficerController@mutationIn')->name('mutation_ins');
Route::get('/mutins',function (){return redirect()->route('mutation_ins', [_UI::$FLAG_UI]);});

Route::get('/mutouts{flag}','OfficerController@mutationOut')->name('mutation_outs');
Route::get('/mutouts',function (){return redirect()->route('mutation_outs', [_UI::$FLAG_UI]);});
Route::post('mutate', 'OfficerController@mutate');

Route::get('/expires{flag}', 'OfficerController@expires')->name('expires');
Route::get('/expires',function (){return redirect()->route('expires', [_UI::$FLAG_UI]);});

Route::get('/approvals{flag}','ApprovalController@approvals')->name('approvals');
Route::get('/approvals',function () {return redirect()->route('approvals',[_UI::$FLAG_UI]);});
Route::post('approvalDelete', 'ApprovalController@delete');
Route::post('approvalVerify', 'ApprovalController@verify');

Route::get('/requestarchives{flag}','RequestArchiveController@requests')->name('request_archives');
Route::get('/requestarchives',function (){return redirect()->route('request_archives', [_UI::$FLAG_UI]);});

Route::get('/archives{flag}','ArchiveController@archives')->name('archives');
Route::get('/archives',function (){return redirect()->route('archives', [_UI::$FLAG_UI]);});
Route::get('/type/{token}', 'ArchiveController@downloadType');
Route::post('typeInsert', 'ArchiveController@insertType');
Route::post('typeEdit', 'ArchiveController@editType');
Route::post('typeDelete', 'ArchiveController@deleteType');
Route::post('typeClear', 'ArchiveController@clearType');

Route::get('/archive/{token}', 'ArchiveController@downloadArchive');
Route::post('archiveDelete', 'ArchiveController@deleteArchive');

Route::get('/registrations{flag}','RegistrationController@registrations')->name('registrations');
Route::get('/registrations',function () {return redirect()->route('registrations', [_UI::$FLAG_UI]);});
Route::get('/registration{flag}','RegistrationController@registration')->name('registration');
Route::post('/registrationInsert','RegistrationController@insert');
Route::post('/registrationVerification','RegistrationController@verification');
Route::post('/registrationDelete','RegistrationController@delete');
Route::post('/registrationClear','RegistrationController@clear');

Route::get('/religions{flag}', 'HomeController@religions');
Route::post('religionInsert', 'HomeController@insertReligion');
Route::post('religionDelete', 'HomeController@deleteReligion');

Route::get('/educations{flag}', 'HomeController@educations');
Route::post('educationInsert', 'HomeController@insertEducation');
Route::post('educationDelete', 'HomeController@deleteEducation');

Route::get('/occupations{flag}', 'HomeController@occupations');
Route::post('occupationInsert', 'HomeController@insertOccupation');
Route::post('occupationDelete', 'HomeController@deleteOccupation');

Route::get('/verify{flag}','VerificationController@verify');

Route::get('/settings',function (){
    _Log::log(_Log::$INFO,'sending get url user settings');
    if (_Authorize::login()) {
        if (_Authorize::admin())
            $param = [
                'citizen','citizen.citreligion','citizen.citeducation','citizen.citoccupation',
                'citizen.citreligion.religion','citizen.citeducation.education','citizen.citoccupation.occupation',
                'citizen.district','citizen.hamlet','citizen.neighboor','citizen.family','officer'
            ];
        else
            $param = [
                'citizen','citizen.citreligion','citizen.citeducation','citizen.citoccupation',
                'citizen.citreligion.religion','citizen.citeducation.education','citizen.citoccupation.occupation',
                'citizen.district','citizen.hamlet','citizen.neighboor','citizen.family'
            ];
        $conf             = (object)null;
        $conf->data       = User::with($param)->firstWhere('id', _Authorize::data()->id);;
        $conf->religion   = Religion::all();
        $conf->education  = Education::all();
        $conf->occupation = Occupation::all();

        _Log::log(_Log::$SUCCESS,'sending get url user settings success');
        _App::page('settings');
        return view('root.setting',compact('conf'));
    }
    _Log::log(_Log::$WARNING, 'sending canceled on login required');
    return view('guest.login');
})->name('settings');

Route::get('/myactivities', 'ActivityController@myActivity')->name('my_activities');
Route::get('/devactivities', 'ActivityController@devActivity')->name('dev_activities');
Route::post('/clearMyActivity', 'ActivityController@clearMy');
Route::post('/clearDevActivity', 'ActivityController@clearDev');
Route::post('/activityDelete', 'ActivityController@deleteActivity');

Route::get('/logs{flag}','LogController@logs')->name('logs');
Route::get('/logs', function (){return redirect()->route('logs', [_UI::$FLAG_UI]);});
Route::post('logClear', 'LogController@clear');

Route::get('/users{flag}','UserController@users')->name('users');

Route::post('admin_on', 'AppController@adminOn');
Route::post('admin_off', 'AppController@adminOff');
Route::post('dev_on', 'AppController@devOn');
Route::post('dev_off', 'AppController@devOff');

Route::post('ckeditor/upload','EditorController@image')->name('ckeditor.image-upload');

Route::get('/test', function (){
//    $str = 'App\\Models\\Citizen';
//    $where = 'where';
//    $first = 'get';
//    $name  = 'name as nama';
//    $fun   = [];
//    $fun['user'] = function($query) use($name) {return $query->select(['id',$name]);};
//    $cit = $str::with($fun)->select(['identity as identitas pengguna', 'gender', 'user_id']);
//    $cit = $cit->$where('gender', 'perempuan');
//    return $cit->$first();




    $syntax = 'data Penduduk pilih(NIK, telepon)';
    //$syntax = 'data Penduduk';
    $model  = \App\Http\back\metasystem\System::process($syntax);
    return $model;
});
