<?php

require_once ("model/SerieStorage.php");
require_once("view/View.php");
require_once("control/Controller.php");

class Router
{




    public function __construct( SerieStorage $serieStor)

    {
        $this->serieStor = $serieStor;
    }




    public function main() {

        $view = new View($this);
        $ctrl = new Controller($view);

        $serieId = key_exists('serie', $_GET) ? $_GET['serie'] : null;
        $tomeId = key_exists('tome', $_GET) ? $_GET['tome'] : null;

        $mangaId = key_exists('manga', $_GET) ? $_GET['manga'] : null;

        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if ($action === null) {
            /* Pas d'action demandée : par défaut on affiche
               * la page d'accueil, sauf si une couleur est demandée,
               * auquel cas on affiche sa page. */
            $action = ($serieId === null) ? "accueil" : "voir";
        }

        if ($action != null) {
            switch ($action) {
                case "voir":
                    if($serieId != null && $tomeId != null){
                        $ctrl->mangaPage($serieId, $tomeId);
                    }
                    //$ctrl->mangaPage($mangaId);

            }

        }


        //$view->makeTestPage();
        //$view->makeMangaPage();
        $view->render();



    }

}