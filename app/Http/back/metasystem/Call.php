<?php /** @noinspection SpellCheckingInspection */


namespace App\Http\back\metasystem;


class Call {
    public static function __callStatic($model, $param) {
        $param      = $param[0];
        $select     = $param['selections'];
        $relation   = $param['relations'];
        $conditions = $param['conditions'];
        $invoke     = $param['invoke'];

        $model = 'App\\Models\\'.$model;
        $model = $model::with($relation)->select($select);

        $model = $model->$invoke();

        foreach ($model as $item) {
            $item->makeHidden(['user_id']);
        }

        $model = preg_replace('/"id":\d+,?/m', '', json_encode($model));

        return $model;
    }
}
