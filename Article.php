<?php
class Article extends Model {
    protected static $table = 'article';
    protected static $primaryKey = 'id';

    public function categorie() {
        return $this->belongs_to('Categorie', 'id_categ');
    }
}