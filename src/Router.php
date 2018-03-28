<?php

require_once ("model/CompteStorageStub.php");
require_once ("model/SerieStorage.php");
require_once("view/View.php");
require_once("control/Controller.php");

session_name("dmWeb");

class Router
{

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function main() {

        session_start();

        $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
        $_SESSION['feedback'] = '';

        $view = new View($this, $feedback);
        $mangadb = new MangaStorageImpl($this->db);
        $seriedb = new SerieStorageImpl($this->db, $mangadb);
        $comptedb = new CompteStorageStub($this->db);

        $ctrl = new Controller($view, $mangadb, $seriedb, $comptedb);

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
                    if ( key_exists('pseudo', $_SESSION))
                        $ctrl->newSerie();
                    break;

                case "sauverNouvelleSerie" :
                    if ( key_exists('pseudo', $_SESSION))
                        $ctrl->saveNewSerie($_POST);
                    break;
                case "creerManga" :
                    if ( key_exists('pseudo', $_SESSION))
                        $ctrl->newManga(null);
                    break;
                case "sauverNouveauManga" :
                    if ( key_exists('pseudo', $_SESSION))
                        $ctrl->saveNewManga($_POST);
                    break;
                case "supprimer" :
                    if ( key_exists('pseudo', $_SESSION)){

                        if ($userPseudo === null || $serieId === null || $tomeId === null) {
                            $view->makeUnknownActionPage();
                        } else {
                            $ctrl->deleteManga($userPseudo, $serieId, $tomeId);
                        }
                    }
                    break;
                case "sauverNouveauCompte" :
                    if ( !key_exists('pseudo', $_SESSION))
                        $ctrl->saveNewCompte($_POST);
                    break;
                case "connexion" :
                    if ( !key_exists('pseudo', $_SESSION))
                        $ctrl->connexion();
                    break;
                case "deconnecter" :
                    if ( key_exists('pseudo', $_SESSION))
                        $ctrl->deconnexion();
                    break;
                case "sauverConnexion" :
                    if ( !key_exists('pseudo', $_SESSION))
                        $ctrl->saveConn($_POST);
                    break;
                case "creerCompte" :
                    if ( !key_exists('pseudo', $_SESSION))
                        $ctrl->newCompte();
                    break;
            }
        }catch (Exception $e) {
            echo $e;
            //$view->makeUnknownActionPage($e);
        }
        $view->render();

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

    public function saveConnexion() {
        return ".?action=sauverConnexion";
    }

    public function accueilPage(){
        return ".?action=accueil";
    }

    public function creerManga(){
        return "?.action=creerManga";
    }

    public function connexionPage(){
        return ".?action=connexion";
    }

    public function creerSerie(){
        return ".?action=creerSerie";
    }

    public function inscriptionPage(){
        return ".?action=creerCompte";
    }

    public function deconnecter(){
        return ".?action=deconnecter";
    }

    public function POSTredirect($url, $feedback) {
        $_SESSION['feedback'] = $feedback;
        header("Location: ".htmlspecialchars_decode($url), true, 303);
        die;
    }
}