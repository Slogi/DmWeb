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



            //RENVOYER SUR LA PAGE D'AJOUR D'UN MANGA
            //$this->v->makeColorPage($colorId, $color);


        }
        else {

            $this->view->makeMangaCreationPage($mb);
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

    public function connexion (){
        $this->view->makeConnexionForm();
    }

    public function saveConn(array $data) {

        $pseudo = $data['pseudo'];
        $mdp = $data['mdp'];
        $compte = $this->comptedb->checkAuth($pseudo, $mdp);

        if ( $compte !== null ){

            $_SESSION['user'] =  $compte;
            echo 'connexion réussie';
        }
        else {
            $this->view->makeConnexionForm();

            

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

    public function confirmMangaDelete($userPseudo, $serieId, $tomeId) {
        /* L'utilisateur confirme vouloir supprimer
        * la couleur. On essaie. */
        $ok = $this->mangadb->delete($serieId, $tomeId);
        if (!$ok) {
            /* La couleur n'existe pas en BD */
            $this->view->makeUnknownMangaPage();
        } else {
            /* Tout s'est bien passé */
            $this->view->makeMangaDeletedPage($userPseudo, $serieId);
        }
    }

    public function modifyManga($userPseudo, $serieId, $tomeId) {
        /* On récupère en BD la couleur à modifier */
        $m = $this->mangadb->read($serieId, $tomeId);
        if ($m === null) {
            $this->view->makeUnknownMangaPage();
        } else {
            /* Extraction des données modifiables */
            $mf = MangaBuilder::buildFromColor($m);
            /* Préparation de la page de formulaire */
            $this->view->makeMangaModifPage($userPseudo, $serieId, $tomeId, $mf);
        }
    }

    public function saveMangaModifications($userPseudo,$serieId,$tomeId, array $data) {
        /* On récupère en BD la couleur à modifier */
        $manga = $this->mangadb->read($serieId, $tomeId);

        if ($manga === null) {
            // La couleur n'existe pas en BD
            $this->view->makeUnknownMangaPage();
        } else {

            $mf = new MangaBuilder($data);
            // Validation des données
            if ($mf->isValidManga()) {
                // Modification de la couleur
                $mf->updateManga($manga);
                // On essaie de mettre à jour en BD.
                //Normalement ça devrait marcher (on vient de
                //récupérer la couleur).

                echo 'date : '.$manga->getDateParu();
                //$ok = $this->mangadb->update($serieId, $manga);
                //if (!$ok)
                    //throw new Exception("Identifier has disappeared?!");
                // Préparation de la page de la couleur
                //$infoSerie = $this->seriedb->read($serieId);
                //$this->view->makeMangaPage($userPseudo, $infoSerie, $manga);
            }
            else {
                $this->view->makeMangaModifPage($userPseudo, $serieId, $tomeId, $mf);
            }
        }

    }




}