<?php /** @noinspection SpellCheckingInspection */


namespace App\Http\back;


class _Token
{
    public static function make($len = 10):string {
        $chars    = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
        $charLen  = strlen($chars);
        $out      = '';
        for ($i   = 0; $i < $len; $i++) {
            $out .= $chars[rand(0, $charLen - 1)];
        }

        return $out;
    }
}
