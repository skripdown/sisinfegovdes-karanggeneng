<?php


namespace App\Http\back;


class _UI
{
    public static $FLAG_UI = ';v=1';
    public static $FLAG_HIDE = ';v=0';
    public static $FLAG_RELATION = ';r=1';
    public static $FLAG_NORELATION = ';r=0';
    public static $FLAG_CONDITION = ';c=1';
    public static $FLAG_ID = ';i=';

    public static function show($flag=''):bool {
        return str_contains($flag, self::$FLAG_UI);
    }

    public static function relation($flag=''):bool {
        return str_contains($flag, self::$FLAG_RELATION);
    }

    public static function single($flag=''):bool {
        return str_contains($flag, self::$FLAG_ID);
    }

    public static function condition($flag=''):bool {
        return str_contains($flag, self::$FLAG_CONDITION);
    }

    public static function relation_with($flag) {
        $pattern = '/;r=[01]\(([\w\.,]*)\)/i';
        preg_match($pattern, $flag, $match);
        return explode(',', $match[1]);
    }

    public static function id($flag='') {
        $pattern = '/;i=([\w]+)/i';
        preg_match($pattern, $flag, $match);
        return $match[1];
    }

    public static function condition_on($flag) {
        $pattern = '/;c=[01]\(([\w\.,]*)\)/i';
        preg_match($pattern, $flag, $match);
        $match = $match[1];
        for ($i = 0; $i<count($match); $i++) {
            $match[$i] = explode('.', $match);
        }
        return $match;
    }
}
