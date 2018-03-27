<?php
/**
 * Created by PhpStorm.
 * User: jujub
 * Date: 23/03/2018
 * Time: 19:01
 */

require_once ("model/Serie.php");
require_once ("model/SerieBuilder.php");
require_once ("model/CompteBuilder.php");

class Controller
{
    protected $view;
    protected $mangadb;
    protected $seriedb;
    protected $comptedb;

    public function __construct(View $v, MangaStorage $mangadb, SerieStorage $seriedb, CompteStorage $comptedb) {
        $this->view = $v;
        $this->mangadb = $mangadb;
        $this->seriedb = $seriedb;
        $this->comptedb = $comptedb;
    }

    public function mangaPage($userPseudo, $serieId, $tomeId) {
        $infoSerie = $this->seriedb->read($serieId);
        $infoManga = $this->mangadb->read($serieId,$tomeId);

        if ($userPseudo === null || $infoManga === null || $infoSerie === null) {
            $this->view->makeUnknownActionPage();

            /* La couleur n'existe pas en BD */
            //$this->v->makeUnknownColorPage();
        } else {

            /* La couleur existe, on prépare la page */
            $this->view->makeMangaPage($userPseudo, $infoSerie, $infoManga);
        }

    }

    public function seriePage($userPseudo, $serieId) {
        $infoSerie = $this->seriedb->read($serieId);
        if ($userPseudo === null || $infoSerie === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeSeriePage($userPseudo, $infoSerie);
    }

    public function userPage($userPseudo) {
        //echo $userPseudo;
        $infoUser = $this->seriedb->readAllUser($userPseudo);
        //var_dump($infoUser);

        if ($userPseudo === null || $infoUser === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeUserPage($userPseudo, $infoUser);


    }

    public function allUsersWithSeriesPage() {
        $allUsersWithSeries = $this->seriedb->readAll();
        if ($allUsersWithSeries === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeAllUsersWithSeriesPage($allUsersWithSeries);
    }

    public function newSerie() {
        echo "newSerie";
        $sb = new SerieBuilder();
        $this->view->makeSerieCreationPage($sb);
    }

    public function saveNewSerie(array $data) {
        $sb = new SerieBuilder($data);
        if ($sb->isValid()){
            $serie = $sb->createSerie();
            $serieId = $this->seriedb->create($serie, "user1");

            //RENVOYER SUR LA PAGE D'AJOUR D'UN MANGA
            //$this->v->makeColorPage($colorId, $color);


        }
        else {
            $this->view->makeSerieCreationPage($sb);
        }
    }

    public function saveNewCompte(array $data) {

        $cb = new CompteBuilder($data);

        if ($cb->isValid($this->comptedb)){
            $compte = $cb->createCompte();
            $pseudo = $this->comptedb->create($compte);

        }
        else {
            $this->view->makeInscriptionPage($cb);
        }
    }

    public function newCompte() {
        $cb = new CompteBuilder();
        $this->view->makeInscriptionPage($cb);
    }
}