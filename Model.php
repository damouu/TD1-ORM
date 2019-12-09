<?php
abstract class Model {
    protected $attr_array = [];
    protected static $table;
    protected static $primaryKey;

    public function __construct($attr_array = []) {
        $this->attr_array = $attr_array;
    }

    public function __get($attr_name) {
        if (array_key_exists($attr_name, $this->attr_array)) {
            return $this->attr_array[$attr_name];
        }

        if (in_array($attr_name, get_class_methods(static::class))) {
            return $this->$attr_name = $this->$attr_name();
        }
    }

    public function __set($attr_name, $value) {
        if (array_key_exists($attr_name, $this->attr_array)) {
            $this->attr_array[$attr_name] = $value;
        }
    }

    public function delete() {
        if (isset(static::$primaryKey)) {
            return Query::table(static::$table)->where([[static::$primaryKey, '=', $this->attr_array[static::$primaryKey]]])->delete();
        }
    }

    public function insert() {
        return Query::table(static::$table)->insert($this->attr_array);
    }

    public static function all() {
        $models_tab = Query::table(static::$table)->get();

        return self::createObjectsFromTab($models_tab, static::class);
    }

    public static function find($first_param, $columns = []){
        $models_tab = [];

        if (gettype($first_param) === 'integer') {
            $models_tab = Query::table(static::$table)->select($columns)->where([[static::$primaryKey, '=', $first_param]])->get();
        }

        if (gettype($first_param) === 'array') {
            if (gettype($first_param[0]) === 'array') {
                $models_tab = Query::table(static::$table)->select($columns)->where($first_param)->get();
            } else {
                $models_tab = Query::table(static::$table)->select($columns)->where([$first_param])->get();
            }
        }

        return self::createObjectsFromTab($models_tab, static::class);
    }

    public static function first($first_param, $columns = []) {
        return static::find($first_param, $columns)[0];
    }

    public function belongs_to($linked_model_name, $foreign_key) {
        $linked_model = new $linked_model_name;
        $model_tab = Query::table(static::$table.','.$linked_model::$table)
                        ->select([$linked_model::$table.'.*'])
                        ->where([
                            [$linked_model::$table.'.'.$linked_model::$primaryKey, '=', $this->$foreign_key],
                            [static::$table.'.'.static::$primaryKey, '=', $this->{static::$primaryKey}]
                        ])
                        ->get()[0];

        return new $linked_model_name($model_tab);
    }

    public function has_many($linked_model_name, $foreign_key) {
        $linked_model = new $linked_model_name;
        $models_tab = Query::table(static::$table.','.$linked_model::$table)
                            ->select([$linked_model::$table.'.*'])
                            ->where([
                                [$linked_model::$table.'.'.$foreign_key, '=', $this->{static::$primaryKey}],
                                [static::$table.'.'.static::$primaryKey, '=', $this->{static::$primaryKey}]
                            ])
                            ->get();

        return self::createObjectsFromTab($models_tab, $linked_model_name);
    }

    private static function createObjectsFromTab(array $models_tab, $class_name) {
        $results_tab = [];

        foreach ($models_tab as $model_tab) {
            $current_model = new $class_name($model_tab);
            $results_tab[] = $current_model;
        }

        return $results_tab;
    }
}
