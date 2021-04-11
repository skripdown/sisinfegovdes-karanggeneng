<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

/** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\back;


use App\Http\back\authorize\Account;
use App\Http\back\authorize\Archive;
use App\Http\back\authorize\Civil;
use App\Http\back\authorize\Developer;
use App\Http\back\authorize\Employee;
use App\Http\back\authorize\Publication;
use App\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class _Authorize
{

    public static function admin(): bool{
        if (!self::login())
            return false;
        return self::role()->admin;
    }

    public static function citizen():bool {
        if (!self::login())
            return false;
        return !self::role()->admin;
    }

    public static function login(): bool {
        return Auth::check();
    }

    public static function data(): ?Authenticatable {
        return Auth::user();
    }

    public static function role(): Role {
        //return self::data()->role()->first();
        return self::data()->role;
    }

    public static function manage($type):bool {
        if (self::citizen())
            return false;
        if ($type == Account::class)
            return self::role()->account;
        elseif ($type == Publication::class)
            return self::role()->publication;
        elseif ($type == Civil::class)
            return self::role()->civil;
        elseif ($type == Employee::class)
            return self::role()->employee;
        elseif ($type == Archive::class)
            return self::role()->archive;
        elseif ($type == Developer::class)
            return self::role()->developer;

        return false;
    }

    public static function chief():bool {
        return self::role()->chief;
    }
}
