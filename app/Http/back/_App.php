<?php /** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\back;


use App\Models\App;

class _App {

    public static function new($user) {
        $app = new App();
        $app->user()->associate($user);
        $app->last_page = 'dashboard';
        $app->save();
    }

    public static function page($page) {
        $app = _Authorize::data()->app;
        $app->last_page = $page;
        $app->save();
    }

    public static function redirect() {
        $app = _Authorize::data()->app;
        return $app->last_page;
    }
}
