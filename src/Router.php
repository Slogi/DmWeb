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


        $userPseudo = key_exists('pseudo', $_GET) ? $_GET['pseudo'] : null;
        $serieId = key_exists('serie', $_GET) ? $_GET['serie'] : null;
        $tomeId = key_exists('tome', $_GET) ? $_GET['tome'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if ($action === null) {
            /* Pas d'action demandée : par défaut on affiche
               * la page d'accueil, sauf si une couleur est demandée,

               * auquel cas on affiche sa page.
            $action = ($mangaId === null)? "accueil": "voir";

               * auquel cas on affiche sa page. */
            $action = ($userPseudo === null && $serieId === null && $tomeId === null) ? "accueil" : "voir";


        }


            try{
                switch ($action) {
                    case "voir":
                        if($userPseudo !== null && $serieId !== null && $tomeId !== null ){
                            $ctrl->mangaPage($userPseudo, $serieId, $tomeId);
                        }
                        elseif($userPseudo !== null && $serieId !== null && $tomeId === null){
                            $ctrl->seriePage($userPseudo, $serieId);
                        }
                        elseif($userPseudo !== null && $serieId === null && $tomeId === null){
                            $ctrl->userPage($userPseudo);
                        }
                        else {
                            $view->makeUnknownActionPage();
                        }
                        break;
                    case "accueil":
                        $ctrl->allUsersWithSeriesPage();
                        break;
                    case "creerSerie" :
                        $ctrl->newSerie();
                        break;
                    case "sauverNouvelleSerie" :
                        $serieId = $ctrl->saveNewSerie($_POST);
                        break;
                    case "creerManga" :
                        $ctrl->newManga(null);
                        break;
                    case "sauverNouveauManga" :
                        $mangaId = $ctrl->saveNewManga($_POST);
                        break;
                }
            }catch (Exception $e) {
                echo $e;
                //$view->makeUnknownActionPage($e);
            }
            $view->render();







                    //$ctrl->mangaPage($mangaId);

            }



        




    public function mangaPage($userPseudo, $serieId, $tomeId) {
        return ".?pseudo=$userPseudo&serie=$serieId&tome=$tomeId";
    }

    public function seriePage($userPseudo, $serieId) {
        return ".?pseudo=$userPseudo&serie=$serieId";
    }

    public function userPage($userPseudo) {
        return ".?pseudo=$userPseudo";
    }

    public function saveCreatedSerie() {
        return ".?action=sauverNouvelleSerie";
    }

    public function saveCreatedManga() {
        return ".?action=sauverNouveauManga";
    }


}