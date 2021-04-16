<?php /** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\back;


use App\Models\App;
use Illuminate\Http\RedirectResponse;

class _App {

    public static function new($user) {
        $app = new App();
        $app->user()->associate($user);
        $app->last_page = 'dashboard';
        $app->save();
    }

    public static function page($page, $flag=null) {
        $app = _Authorize::data()->app;
        $app->last_page = $page;
        $app->flag      = $flag;
        $app->save();
    }

    public static function direct(): RedirectResponse {
        $app = _Authorize::data()->app;
        if ($app->flag != null)
            return redirect()->route($app->last_page, [$app->flag]);
        return redirect()->route($app->last_page);
    }
}
