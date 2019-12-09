<?php
class Categorie extends Model {
    protected static $table = 'categorie';
    protected static $primaryKey = 'id';

    public function articles() {
        return $this->has_many('Article', 'id_categ');
    }
}