<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 22/03/2018
 * Time: 17:51
 */

require_once("view/View.php");
require_once("control/Controller.php");

class Router
{
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

    }
}