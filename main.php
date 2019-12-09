<?php
require_once "Query.php";
require_once "ConnectionFactory.php";
require_once "Model.php";
require_once "Article.php";
require_once "Categorie.php";

$conf = parse_ini_file('conf/config.ini');
ConnectionFactory::makeConnection($conf);


/*
$article = new Article(['text' => 'test', 'id' => 1]);
echo "text avant: $article->text";
echo "id avant: $article->id";

$article->text = 'teeeeeest';
$article->id = 2;

echo "text après: $article->text";
echo "id après: $article->id"; */

/*
$article = new Article(['id' => 67]);
$article->delete();  */

/*
$article = new Article(['nom' => 'tesla',
                        'descr' => 'voiture electrique',
                        'tarif' => 100000, 'id_categ' => 1]);
$article->insert(); */

/*$allArticles = Article::all();

foreach ($allArticles as $article) {
    echo "Nom : $article->nom\n";
}*/

/*$article = Article::find(['nom', '=','tesla'])[0];
var_dump($article);
var_dump($article->belongs_to('Categorie', 'id_categ')); */

/*$categorie = Categorie::first(1);
var_dump($categorie->has_many('Article', 'id_categ')); */

/*$categorie = Article::first(106)->categorie();
var_dump($categorie);
$articles = Categorie::first(1)->articles();
var_dump($articles);*/

/*$categorie = Categorie::first(1);
var_dump($categorie->articles);*/

$article = Article::first(66);
var_dump($article->all);