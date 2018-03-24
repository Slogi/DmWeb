<?php

require_once ("model/SerieStorage.php");



require_once("view/View.php");
require_once("control/Controller.php");

class Router
{
<<<<<<< HEAD
    public function __construct( SerieStorage $serieStor)
    {
        $this->serieStor = $serieStor;
    }

    public function main(){
        var_dump($this->serieStor->read(1));
=======
    /*
    public function __construct(SerieStorage $seriedb) {
        $this->seriedb = $seriedb;
    }*/

    public function main() {
        $view = new View($this);
        $ctrl = new Controller($view);


        $mangaId = key_exists('manga', $_GET)? $_GET['manga']: null;
        $action = key_exists('action', $_GET)? $_GET['action']: null;

        if ($action === null) {
            /* Pas d'action demandée : par défaut on affiche
               * la page d'accueil, sauf si une couleur est demandée,
               * auquel cas on affiche sa page. */
            $action = ($mangaId === null)? "accueil": "voir";
        }

        if($action != null){
            switch ($action) {
                case "voir":
                    $ctrl->mangaPage($mangaId);

            }

        }


        //$view->makeTestPage();
        //$view->makeMangaPage();
        $view->render();

>>>>>>> master
    }
}