<?php /** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\back;


use App\Http\back\authorize\Developer;
use App\Http\back\userinfo\UserInfo;
use App\Models\Browser;
use App\Models\Client;
use App\Models\Device;
use App\Models\Ip;
use App\Models\Log;
use App\Models\Logbrowser;
use App\Models\Logdevice;
use App\Models\Logip;
use App\Models\Logoperatingsystem;
use App\Models\Operatingsystem;
use App\Models\User;

class _Log
{

    public static $SUCCESS = 1;
    public static $INFO    = 0;
    public static $WARNING = -1;
    public static $DANGER  = -2;

    private static function server(): Client {
        if (Client::all()->where('name','SYSTEM')->where('identity','SERVER')->count() == 0) {
            $server = new Client();
            $server->name = 'SYSTEM';
            $server->identity = 'SERVER';
            $server->save();

            return $server;
        }

        return Client::all()->where('name','SYSTEM')->firstWhere('identity', 'SERVER');
    }

    private static function serverAttr():array {
        if (Ip::all()->where('address', 'SYSTEM')->count() == 0) {
            $ip = new Ip();
            $ip->from_name     = 'SYSTEM';
            $ip->from_identity = 'SYSTEM';
            $ip->address       = 'SYSTEM';
            $ip->save();
        }
        else
            $ip = Ip::all()->firstWhere('address', 'SYSTEM');

        if (Device::all()->where('name', 'SYSTEM')->count() == 0) {
            $device = new Device();
            $device->name = 'SYSTEM';
            $device->save();
        }
        else
            $device = Device::all()->firstWhere('name', 'SYSTEM');

        if (Browser::all()->where('name', 'SYSTEM')->count() == 0) {
            $browser = new Browser();
            $browser->name = 'SYSTEM';
            $browser->save();
        }
        else
            $browser = Browser::all()->firstWhere('name', 'SYSTEM');

        if (Operatingsystem::all()->where('name', 'SYSTEM')->count() == 0) {
            $os = new Operatingsystem();
            $os->name = 'SYSTEM';
            $os->save();
        }
        else
            $os = Operatingsystem::all()->firstWhere('name', 'SYSTEM');

        return [$ip, $device, $browser, $os];
    }

    private static function client(): Client {
        $name = 'anonim';
        $identity = 'unknown';
        if (_Authorize::login()) {
            $name = _Authorize::data()->name;
            $identity = _Authorize::data()->identity;
        }

        if (Client::all()->where('name',$name)->where('identity',$identity)->count() == 0) {
            $client = new Client();
            $client->name = $name;
            $client->identity = $identity;
            $client->save();

            return $client;
        }

        return Client::all()->where('name', $name)->firstWhere('identity', $identity);
    }

    private static function OS(): Operatingsystem {
        $name = UserInfo::get_os();
        if (Operatingsystem::all()->where('name',$name)->count() == 0) {
            $os = new Operatingsystem();
            $os->name = $name;
            $os->save();

            return $os;
        }

        return Operatingsystem::all()->firstWhere('name', $name);
    }

    private static function IP(): Ip {
        $address = $_SERVER['REMOTE_ADDR'];
        $name = 'anonim';
        $identity = 'unknown';
        $logged_in = false;
        if (_Authorize::login()) {
            $name = _Authorize::data()->name;
            $identity = _Authorize::data()->identity;
            $logged_in = true;
        }
        if (Ip::all()->where('address', $address)->where('from_name',$name)->where('from_identity',$identity)->count() == 0) {
            $ip = new Ip();
            $ip->address = $address;
            $ip->logged_in = $logged_in;
            $ip->from_name = $name;
            $ip->from_identity = $identity;
            $ip->save();

            return $ip;
        }
        $ip = Ip::all()->where('from_name',$name)->where('from_identity',$identity)->firstWhere('address', $address);

        return $ip;
    }

    private static function browser(): Browser {
        $name = UserInfo::get_browser();
        if (Browser::all()->where('name',$name)->count() == 0) {
            $browser = new Browser();
            $browser->name = $name;
            $browser->save();

            return $browser;
        }

        return Browser::all()->firstWhere('name', $name);
    }

    private static function device(): Device {
        $name = UserInfo::get_device();
        if (Device::all()->where('name',$name)->count() == 0) {
            $device = new Device();
            $device->name = $name;
            $device->save();

            return $device;
        }

        return Device::all()->firstWhere('name', $name);
    }

    public static function OSes() {
        return Operatingsystem::all();
    }

    public static function IPs() {
        return Ip::all();
    }

    public static function browsers() {
        return Browser::all();
    }

    public static function devices() {
        return Device::all();
    }

    public static function accounts() {
        return User::all();
    }

    public static function system($status=0, $message='no message') {
        $log = new Log();
        $serverAtt = self::serverAttr();
        $ip        = $serverAtt[0];
        $device    = $serverAtt[1];
        $browser   = $serverAtt[2];
        $os        = $serverAtt[3];
        $log->status  = $status;
        $log->message = $message;
        $log->client()->associate(self::server());
        $log->save();
        $logOs = new Logoperatingsystem();
        $logOs->log()->associate($log);
        $logOs->operatingsystem()->associate($os);
        $logOs->save();
        $logBrowser = new Logbrowser();
        $logBrowser->log()->associate($log);
        $logBrowser->browser()->associate($browser);
        $logBrowser->save();
        $logDevice = new Logdevice();
        $logDevice->log()->associate($log);
        $logDevice->device()->associate($device);
        $logDevice->save();
        $logIp = new Logip();
        $logIp->log()->associate($log);
        $logIp->ip()->associate($ip);
        $logIp->save();
    }

    public static function log($status=0, $message='no message') {
        $log     = new Log();
        $client  = self::client();
        $ip      = self::IP();
        $device  = self::device();
        $browser = self::browser();
        $os      = self::OS();
        $log->status = $status;
        $log->message = $message;
        $log->client()->associate($client);
        $log->save();
        $logIp = new Logip();
        $logIp->log()->associate($log);
        $logIp->ip()->associate($ip);
        $logIp->save();
        $logDevice = new Logdevice();
        $logDevice->log()->associate($log);
        $logDevice->device()->associate($device);
        $logDevice->save();
        $logBrowser = new Logbrowser();
        $logBrowser->log()->associate($log);
        $logBrowser->browser()->associate($browser);
        $logBrowser->save();
        $logOs = new Logoperatingsystem();
        $logOs->log()->associate($log);
        $logOs->operatingsystem()->associate($os);
        $logOs->save();

    }

    public static function clear($request):array {
        if (_Authorize::chief() || _Authorize::manage(Developer::class)) {
            $len = Log::all()->count();
            if ($len > 18) {
                $logs = Log::all();
                try {
                    for ($i = 17; $i < count($logs); $i++) {
                        $log = $logs[$i];
                        $log->delete();
                    }
                } catch (\Exception $e) {
                    $status = ['status'=>'error','message'=>'Gagal menghapus log proses'];
                    return array_merge($request, $status);
                }
                $status     = ['status'=>'success','message'=>'Berhasil membersihkan '.$len.' log proses pada sistem'];
            }
            else {
                $status = ['status'=>'error','message'=>'Gagal menghapus log proses'];
            }
        }
        else {
            $status = ['status'=>'error','message'=>'Gagal menghapus log proses'];
        }

        return array_merge($request->all(), $status);
    }
}
