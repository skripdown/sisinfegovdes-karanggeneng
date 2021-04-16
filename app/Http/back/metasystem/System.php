<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SpellCheckingInspection */


namespace App\Http\back\metasystem;

use App\Models\Systemattr;

class System {

    private static function REGget($regex, $text) {
        preg_match($regex, $text, $match);
        return $match[1];
    }

    private static function REGclear($regex, $text) {
        return preg_replace($regex, '', $text);
    }

    private static function REGmatch($regex, $text) {
        return preg_match($regex, $text);
    }

    //PARSE SCRIPT
    private static function lex($raw): array {
        $STATIC_COL_ALL    = "SEMUA";

        $REGEX_CLEAR_SPACE = "/ *\n+ */m";
        $REGEX_MODEL       = "/ *\bdata +\b([A-Z_$][\w$]+)\b/m";
        $REGEX_PILIH       = "/ *\bpilih *\( *(SEMUA|[ _\w,'\"]+) *\)/m";
        $REGEX_SPLIT_PILIH = "/ *, */m";
        $REGEX_VAL_PILIH   = "/([\w]+) *(['\"]([\w '\"]+)?['\"])?/m";

        //PREPROCESS & MODEL
        $raw   = preg_replace($REGEX_CLEAR_SPACE, ' ', $raw);
        $model = self::REGget($REGEX_MODEL, $raw);
        $raw   = self::REGclear($REGEX_MODEL, $raw);

        if (self::REGmatch($REGEX_PILIH, $raw)) {
            //COLUMN SELECTION
            $columns       = self::REGget($REGEX_PILIH, $raw);

            if ($columns  != $STATIC_COL_ALL) {
                $arr       = [];
                $raw       = self::REGclear($REGEX_PILIH, $raw);
                $columns   = preg_split($REGEX_SPLIT_PILIH, $columns);
                foreach ($columns as $column) {
                    preg_match($REGEX_VAL_PILIH, $column, $match);
                    if (count($match) == 4) {
                        array_push($arr, ['label'=>$match[1],'alias'=>' as '.$match[3]]);
                    }
                    else {
                        array_push($arr, ['label'=>$match[1],'alias'=>' as '.$match[1]]);
                    }
                }
                $columns = $arr;
            }
            else
                $columns = '*';

            //COMPACT RESULT
            return [
                'model'=>$model,
                'columns'=>$columns,
            ];
        }
        return [
            'model'=>$model,
            'columns'=>''
        ];
    }

    //TOKENIZE SCRIPT INTO ELOQUENT ARRAY
    private static function tokenize($token): array {
        $select   = '*';
        $model    = \App\Models\System::with('systemattrs')->firstWhere('label', $token['model']);
        $mdtmp    = strtolower($model->model);
        $attrs    = $model->systemattrs()->get();
        $keys     = [];
        $sources  = [];
        $used_src = [];

        foreach ($attrs as $attr) {
            $keys[$attr->label] = $attr;
        }

        if ($token['columns'] != $select && $token['columns'] != '') {
            $called_model = [];
            $keys_model   = [];
            $relations    = [];
            $selection    = [];
            $columns      = $token['columns'];
            foreach ($columns as $column) {
                $label = $column['label'];
                $alias = $column['alias'];
                $key   = $keys[$label];

                if ($key->relation) {
                    if (!array_key_exists($key->model, $called_model)) {
                        array_push($keys_model, $key->model);
                        $called_model[$key->model] = true;
                        if ($keys[$label]->source) {
                            $relations[$key->model]    = ['id'];
                            if (!array_key_exists($key->model, $used_src)) {
                                $used_src[$key->model] = true;
                                array_push($sources, $key->model);
                            }
                        }
                        else
                            $relations[$key->model]    = [$mdtmp.'_id'];
                    }
                    array_push($relations[$key->model], $key->pointer.$alias);
                }
                else
                    array_push($selection, $key->pointer.$alias);
            }

            foreach ($sources as $source)
                array_push($selection, $source.'_id');

            return [
                'next'=>true,
                'model'=>$model->id,
                'keys_model'=>$keys_model,
                'relations'=>$relations,
                'selection'=>$selection
            ];
        }
        elseif ($token['columns'] == $select) {

        }
        else {
            $columns = [];
            foreach ($attrs as $attr) {
                array_push($columns, $attr->label);
            }
            return [
                'next'=>false,
                'model'=>$model->id,
                'keys_model'=>[],
                'relations'=>[],
                'selection'=>$columns
            ];
        }
    }

    //CONSTRUCT LARAVEL ELOQUENT
    private static function construct($param) {
        $invoke      = 'get';
        $keys_model  = $param['keys_model'];
        $relations   = $param['relations'];
        $selection   = $param['selection'];
        $condition   = [];
        $model       = \App\Models\System::all()->firstWhere('id', $param['model']);
        $model       = $model->model;
        $relationArr = [];
        foreach ($keys_model as $item) {
            $sel                = $relations[$item];
            $relationArr[$item] = function ($query) use ($sel) {
                return $query->select($sel);
            };
        }
        if (count($selection) == 0)
            $selection = ['id'];

        //LOGICAL CONDITION CONSTRUCTED HERE

        $param = [
            'selections' => $selection,
            'relations'  => $relationArr,
            'conditions' => $condition,
            'invoke'     => $invoke
        ];

        //return $param['relations'];
        return Call::$model($param);
    }

    //CLEAR PRIMARY & RELATIONAL ATTRIBUTE
    private static function clear($json) {
        $objects = json_decode($json);

        return $objects;
    }

    public static function process($syntax) {
        $result = self::lex($syntax);
        $result = self::tokenize($result);
        if ($result['next']) {
            $result = self::construct($result);
        }

        return self::clear($result);
    }

    //INIT SYSTEM ATTRIBUTES ON MODELS
    public static function init($name, $label, $attributes) {
        $model = new \App\Models\System();
        $model->model = $name;
        $model->label = $label;
        $model->save();

        foreach ($attributes as $attribute) {
            if (!array_key_exists('model', $attribute))
                $attrmodel  = '';
            else
                $attrmodel  = $attribute['model'];
            $attr           = new Systemattr();
            $attr->label    = $attribute['label'];
            $attr->pointer  = $attribute['pointer'];
            $attr->relation = $attrmodel != '';
            $attr->model    = $attrmodel;
            if ($attrmodel != '')
                $attr->source   = $attribute['source'];
            $attr->system()->associate($model);
            $attr->save();
        }
    }
}
