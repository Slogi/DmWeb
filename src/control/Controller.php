<?php


require_once ("model/Serie.php");
require_once ("model/SerieBuilder.php");
require_once ("model/CompteBuilder.php");
require_once ("model/MangaBuilder.php");


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
        $sb = new SerieBuilder();
        $this->view->makeSerieCreationPage($sb);
    }

    public function saveNewSerie(array $data) {
        $sb = new SerieBuilder($data);
        if ($sb->isValidSerie()){
            $serie = $sb->createSerie();
            $serieId = $this->seriedb->create($serie, "user1");

            $this->newManga($serieId);

            //RENVOYER SUR LA PAGE D'AJOUR D'UN MANGA
            //$this->v->makeColorPage($colorId, $color);


        }
        else {

            $this->view->makeSerieCreationPage($sb);
        }
    }

    public function newManga($idSerie) {
        if($idSerie === null){
            $this->view->makeUnknownActionPage();
        }
        else {
            $sb = new MangaBuilder();
            $this->view->makeMangaCreationPage($sb, $idSerie);
        }

    }

    public function saveNewManga(array $data) {
        $idSerie = $data['idSerie'];
        $mb = new MangaBuilder($data);
        if ($mb->isValidManga()){
            $manga = $mb->createManga();
            if($manga->getDateParu() === ''){
                $manga->setDefaultDateParu();
            }
            $mangaId = $this->mangadb->create($manga, $idSerie);
            $this->view->makeMangaCreatedPage($idSerie, $_SESSION['user']);

        }
        else {

            $this->view->makeMangaCreationPage($mb, $idSerie);
        }
    }

    public function saveNewCompte(array $data) {

        $cb = new CompteBuilder($data);

        if ($cb->isValid($this->comptedb)){
            $compte = $cb->createCompte();
            $pseudo = $this->comptedb->create($compte);
            $this->view->makeCompteCreatedPage();
        }
        else {
            $this->view->makeInscriptionPage($cb);
        }
    }

    public function newCompte() {
        $cb = new CompteBuilder();
        $this->view->makeInscriptionPage($cb);
    }

    public function connexion (){
        $this->view->makeConnexionForm();
    }

    public function saveConn(array $data) {

        $pseudo = $data['pseudo'];
        $mdp = $data['mdp'];
        $compte = $this->comptedb->checkAuth($pseudo, $mdp);

        if ( $compte !== null ){

            $_SESSION['pseudo'] =  $compte;
            $_SESSION['nom'] =  $compte;
            $_SESSION['prenom'] =  $compte;
            $_SESSION['dateBirth'] =  $compte;
            $this->view->makeConnSucessPage();
        }
        else {
            $this->view->makeConnexionForm("Vos identifiants ne sont pas bons");

        }

    }

    public function deleteManga($userPseudo, $serieId, $tomeId) {
        /* On récupère la couleur en BD */
        $manga = $this->mangadb->read($serieId, $tomeId);
        if ($manga === null) {
            /* La couleur n'existe pas en BD */
            $this->view->makeUnknownMangaPage();
        } else {
            /* La couleur existe, on prépare la page */
            $this->view->makeMangaDeletePage($userPseudo, $serieId, $manga);
        }
    }
}