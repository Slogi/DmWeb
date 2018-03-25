<?php

require_once ("model/SerieStorage.php");
require_once("view/View.php");
require_once("control/Controller.php");

class Router
{




    public function __construct($db){
               $this->db = $db;
    }

    public function main() {


        $view = new View($this);
        $mangadb = new MangaStorageImpl($this->db);
        $seriedb = new SerieStorageImpl($this->db, $mangadb);



        $ctrl = new Controller($view, $mangadb, $seriedb);

       // echo $_GET['pseudo'];
       // echo $_GET['serie'];
       // echo $_GET['tome'];

        $userPseudo = key_exists('pseudo', $_GET) ? $_GET['pseudo'] : null;
        $serieId = key_exists('serie', $_GET) ? $_GET['serie'] : null;
        $tomeId = key_exists('tome', $_GET) ? $_GET['tome'] : null;

        //$mangaId = key_exists('manga', $_GET) ? $_GET['manga'] : null;

        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if ($action === null) {
            /* Pas d'action demandée : par défaut on affiche
               * la page d'accueil, sauf si une couleur est demandée,

               * auquel cas on affiche sa page.
            $action = ($mangaId === null)? "accueil": "voir";

               * auquel cas on affiche sa page. */
            $action = ($userPseudo === null && $serieId === null && $tomeId === null) ? "accueil" : "voir";
            //echo $action;

        }

        if ($action != null) {
            switch ($action) {
                case "voir":
                    if($userPseudo !== null && $serieId !== null && $tomeId !== null ){
                        //echo $serieId;
                        //echo $tomeId;
                        $ctrl->mangaPage($userPseudo, $serieId, $tomeId);
                    }
                    if($serieId === null && $tomeId !== null){
                        //echo $serieId;
                        //echo $tomeId;
                        $view->makeUnknownActionPage();
                    }
                    if($userPseudo !== null && $serieId !== null && $tomeId === null){
                        $ctrl->seriePage($userPseudo, $serieId);
                    }
                    if($userPseudo !== null && $serieId === null && $tomeId === null){
                        //echo 'aaa';
                        //echo $userPseudo;
                        $ctrl->userPage($userPseudo);
                    }

                    //$ctrl->mangaPage($mangaId);

            }

        }

        }




    public function mangaPage($serieId, $tomeId) {
        return ".?serie=$serieId&tome=$tomeId";
    }

}