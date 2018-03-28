<?php

require_once ("model/CompteStorageStub.php");
require_once ("model/SerieStorage.php");
require_once("view/View.php");
require_once("control/Controller.php");

session_name("dmWeb");
session_start();

class Router
{

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function main() {


        $view = new View($this);
        $mangadb = new MangaStorageImpl($this->db);
        $seriedb = new SerieStorageImpl($this->db, $mangadb);
        $comptedb = new CompteStorageStub($this->db);


        $ctrl = new Controller($view, $mangadb, $seriedb, $comptedb);



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
                        //echo $serieId;
                        //echo $tomeId;
                        $ctrl->mangaPage($userPseudo, $serieId, $tomeId);
                    }
                    elseif($userPseudo !== null && $serieId !== null && $tomeId === null){
                        $ctrl->seriePage($userPseudo, $serieId);
                    }
                    elseif($userPseudo !== null && $serieId === null && $tomeId === null){
                        //echo 'aaa';
                        //echo $userPseudo;
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
                    echo $action;
                    $ctrl->newSerie();
                    break;
                case "creerCompte" :
                    echo $action;
                    $ctrl->newCompte();
                    break;
                case "sauverNouvelleSerie" :
                    $ctrl->saveNewSerie($_POST);
                    break;
                case "sauverNouveauCompte" :

                    $ctrl->saveNewCompte($_POST);
                    break;
                case "connexion" :
                    echo $action;
                    $ctrl->connexion();
                    break;
                case "sauverConnexion" :

                    $ctrl->saveConn($_POST);
                    break;

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
                    case "supprimer" :
                        if ($userPseudo === null || $serieId === null || $tomeId === null) {
                            $view->makeUnknownActionPage();
                        } else {
                            $ctrl->deleteManga($userPseudo, $serieId, $tomeId);
                        }
                        break;
                    case "confirmerSuppression":
                        if ($userPseudo === null || $serieId === null || $tomeId === null) {
                            $view->makeUnknownActionPage();
                        } else {
                            $ctrl->confirmMangaDelete($userPseudo, $serieId, $tomeId);
                        }
                        break;
                    case "modifier":
                        if ($userPseudo === null || $serieId === null || $tomeId === null) {
                            $view->makeUnknownActionPage();
                        } else {
                            $ctrl->modifyManga($userPseudo, $serieId, $tomeId);
                        }
                        break;
                    case "sauverModifs":
                        if ($userPseudo === null || $serieId === null || $tomeId === null) {
                            $view->makeUnknownActionPage();
                        } else {
                            $ctrl->saveMangaModifications($userPseudo,$serieId,$tomeId, $_POST);
                        }
                        break;


                }
            }catch (Exception $e) {
                echo $e;
                //$view->makeUnknownActionPage($e);
            }
            $view->render();







                    //$ctrl->mangaPage($mangaId);

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


    public function saveCreatedCompte() {
        return ".?action=sauverNouveauCompte";
    }

    public function saveCreatedManga() {
        return ".?action=sauverNouveauManga";
    }

    public function mangaDeletePage($userPseudo, $serieId, $tomeId) {
        return ".?pseudo=$userPseudo&serie=$serieId&tome=$tomeId&action=supprimer";
    }

    public function confirmMangaDelete($userPseudo,$serieId, $tomeId) {
        return ".?pseudo=$userPseudo&serie=$serieId&tome=$tomeId&action=confirmerSuppression";
    }

    public function mangaModifPage($userPseudo, $serieId, $tomeId) {
        return ".?pseudo=$userPseudo&serie=$serieId&tome=$tomeId&action=modifier";
    }

    public function updateModifiedManga($userPseudo, $serieId, $tomeId) {
        return ".?pseudo=$userPseudo&serie=$serieId&tome=$tomeId&action=sauverModifs";
    }



    public function saveConnexion() {
        return ".?action=sauverConnexion";
    }
}